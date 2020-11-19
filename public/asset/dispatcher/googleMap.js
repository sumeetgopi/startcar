<script>
let countryId = 1, categoryId = '', currency = '£';
let pickupLocation = dropLocation = '';
let pickupLat = pickupLong = dropLat = dropLong = distance = totalDistance = fare = 0;
let pickupHidden = '', dropHidden = '';
let map, markers = [];
let locations = [];
let mapOptions = {
    componentRestrictions: { country: 'uk' }
};

let distanceDirection, distanceDisplay;
let lat = 51.4730743, lng = -0.4164674;
let from, to;

function initMap() {
    setValues();
    setMapCountry(1); // uk
    setDefaultMap();

    from = document.getElementById('from-input');
    setMapSearch(from, 0);

    to = document.getElementById('to-input');
    setMapSearch(to, 1);
    
}

function setDefaultMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: lat, lng: lng },
        zoom: 12,
        mapTypeId: 'roadmap'
    });
}

function setMapCountry(countryCode) {
    if (countryCode == 1) { // uk -> hounslow
        lat = 51.4730743;
        lng = -0.4164674;
        currency = '£';
        mapOptions.componentRestrictions = { country: 'uk' };
    }
    else if (countryCode == 2) { // in -> jalandhar
        lat = 31.3171106;
        lng = 75.5588657;
        currency = '₹';
        mapOptions.componentRestrictions = { country: 'in' };
    }
    setDefaultMap();
}

function setMapSearch(input, index) 
{
    let autocomplete = new google.maps.places.Autocomplete(input, mapOptions);

    markers[index] = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });

    map.addListener('bounds_changed', function() {
        autocomplete.setBounds(map.getBounds());
    });

    distanceDirection =   new google.maps.DirectionsService();
    distanceDisplay   =   new google.maps.DirectionsRenderer();

    let bounds = new google.maps.LatLngBounds();

    autocomplete.addListener('place_changed', function() {
        if(markers[index] !== undefined) {
            markers[index].setVisible(false);
        }

        var place = autocomplete.getPlace();
        if (!place.geometry) {
            alert('No details available for input: ' + place.name);
            return;
        }
        
        console.log(place.formatted_address, place.geometry.location);
        locations[index] = place.geometry.location;
        if(index == 0) {
            pickupLat = locations[index].lat();
            pickupLong = locations[index].lng();
            pickupLocation = place.formatted_address;
            
            setValues();

            $("#from-input").attr('disabled', 'disabled');
            if(dropLat == 0 && dropLong == 0) {
                $("#to-input").attr('disabled', false).focus();
            }
        }
        else {
            dropLat = locations[index].lat();
            dropLong = locations[index].lng();
            dropLocation = place.formatted_address;

            $("#from-input").attr('disabled', 'disabled');
            $("#to-input").attr('disabled', 'disabled');
            
            setValues();                    
        }
                        
        if(place.geometry.viewport) {
            bounds.union(place.geometry.viewport);
        } else {
            bounds.extend(place.geometry.location);
        }

        markers[index].setPosition(place.geometry.location);
        markers[index].setVisible(true);
        distanceDisplay.setMap(map);

        if(from.value != '' && to.value != '') {
            markers[0].setVisible(false);
            markers[1].setVisible(false);
            
            createTrack();
        }
    });
}

function createDistance(start, end) {
    distance = google.maps.geometry.spherical.computeDistanceBetween(start, end);
    // return (distance / 1000).toFixed(2); // KM
    return (distance * 0.00062137).toFixed(2); // MI
}

function createTrack() 
{
    // distance code start
    let start = new google.maps.LatLng(locations[0].lat(), locations[0].lng());
    let end = new google.maps.LatLng(locations[1].lat(), locations[1].lng());
    totalDistance = createDistance(start, end);
    setValues();
    // distance code end

    let bounds = new google.maps.LatLngBounds();
    bounds.extend(start);
    bounds.extend(end);
    map.fitBounds(bounds);

    let request = {
        travelMode: google.maps.TravelMode.DRIVING,
        origin: start,
        destination: end,
    };

    distanceDirection.route(request, function(response, status) {
        if(status == google.maps.DirectionsStatus.OK) {
            distanceDisplay.setDirections(response);
            distanceDisplay.setMap(map);
        }
        else {
            alert("Error: From " + start.toUrlValue(6) + " to " + end.toUrlValue(6) + " failed : " + status);
        }
    });

    getFareAndDriver();
}

function getFareAndDriver() {
    if(categoryId == '' || pickupLat == 0 || pickupLong == 0 ) {
        alert('Please choose the cab, pickup & drop location for fare.');
        return false;
    }
    

    let route = $('.__category').attr('data-route');
    let data = {
        country_id: countryId,
        category_id: categoryId,
        lat: pickupLat,
        long: pickupLong,
        distance: distance,
        pickup_hidden:pickupHidden,
        drop_hidden:dropHidden,
    };

    ajaxFire(route, data, function(data) {
        
        if (data.success) {
            $(this).find("button[type='submit']").prop('disabled', true);

            $('__driver').html(res.result.driver);
            $('.__driver').select2();
            fare = res.result.fare;
            setValues();
        } else {
            if (data.status == 206) {
                hideLoader();
                
                $.each(data.message, function(i, v) {
                    var error = '<div class="error">' + v + '</div>';
                    var split = i.split('.');
                    if (split[2]) {
                        var ind = split[0] + '[' + split[1] + ']' + '[' + split[2] + ']';
                        form.find("[name='" + ind + "']").parent().append(error);
                    } else if (split[1]) {
                        var ind = split[0] + '[' + split[1] + ']';
                        form.find("[name='" + ind + "']").parent().append(error);
                    } else {
                        form.find("[name='" + i + "']").parent().append(error);
                    }
                });
            } else if (data.status == 207) {
                hideLoader();

                if (data.message != '') {
                    $('.__modal_message').html(data.message);
                    $('.__modal').modal('show');
                }

                if (data.extra.reload) {
                    $('.__modal').on('hidden.bs.modal', function(e) {
                        window.location.reload();
                    });
                }

                if (data.extra.redirect) {
                    if (data.message != '') {
                        $('.__modal').on('hidden.bs.modal', function(e) {
                            window.location.href = data.extra.redirect;
                        });
                    } else {
                        window.location.href = data.extra.redirect;
                    }
                }
            }
        }


        if(res.status == 206){

        }
        if (res.success) {
            $('__driver').html(res.result.driver);
            $('.__driver').select2();

            fare = res.result.fare;
            setValues();
        }
        
    });
}

$('body').on('change', '.__country', function(e) {
    pickupLat = pickupLong = dropLat = dropLong = distance = totalDistance = fare = 0;
    countryId = $(this).val();
    setMapCountry(countryId);
    setValues();

    from.value = '';
    to.value = '';
    setMapSearch(from, 0);
    setMapSearch(to, 1);

    $("#from-input").attr('disabled', false);
    $("#to-input").attr('disabled', true);
});

$('body').on('change', '.__category', function(e) {
    categoryId = $(this).val();
    if(categoryId == '') {
        distance = totalDistance = fare = 0;
        setValues();
    }
    
    getFareAndDriver();      
    
});

$("#clearFrom").on("click", function() {
    pickupLat = pickupLong = 0;
    setValues();

    $('#from-input').removeAttr('disabled').val('').focus();
});

$("#clearTo").on("click", function(){
    dropLat = dropLong = 0;
    setValues();
    $('#to-input').removeAttr('disabled').val('').focus();
});

$('#from-input').focusout(function() {
    $('#to-input').removeAttr('disabled');
    if(pickupLat == 0 && pickupLong == 0) {
        $(this).val('');
    }

    if(dropLat != 0 && dropLat != 0) {
        $("#to-input").attr('disabled', 'disabled');
    }
});

$('#to-input').focusout(function() {
    if(dropLat == 0 && dropLong == 0) {
        $(this).val('');
    }
});

function setValues() {
    $('#pickup_lat').val(pickupLat);
    $('#pickup_long').val(pickupLong);
    $('#drop_lat').val(dropLat);
    $('#drop_long').val(dropLong);
    $('#pickup_hidden').val(dropHidden);
    $('#drop_hidden').val(pickupHidden);

    let fareText = fare + ' ' + currency;
    $('#fare').text(fareText);
    $('#fare_hidden').val(fare);

    let distanceText = totalDistance.toString() + ' mi';
    $('#distance').text(distanceText);
    $('#ride_distance').val(distance);
    $('#ride_distance_text').val(distanceText);

    $("#pickup_hidden").val(pickupLocation);
    $("#drop_hidden").val(dropLocation);
}

$('.mobile_search').click(function(e) {
    e.preventDefault();

    let mobileNumber = $('.__customer_mobile').val();
    let route = $(this).attr('data-route');
    let data = { mobile_number: mobileNumber };

    $('.search_icon').hide();
    $('.loading_icon').show();
    $('.mobile_error').text('');

    ajaxFireWithLoader(route, data, function(res) {
        if (res.success) {
            $('.__customer_name').val(res.customer_name);
            $('.__customer_email').val(res.email);                    
        }
        else {
            if(res.status == 206) {
                $('.mobile_error').text('Please fill 10 digit number');
                
            }
            else {
                $('.__customer_name').val('');
                $('.__customer_email').val('');
                $('.mobile_error').text('No Record Found');
            }
        }

        $('.search_icon').show();
        $('.loading_icon').hide();
    });
});
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADlk166150RMLLGby78Ayq9kUKyAdHtp0&libraries=places&callback=initMap"></script>