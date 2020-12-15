let pageContainer = $('#pagination');
let url = pageContainer.data('url');
let sortEntity = '';
let sortOrder = '';
let perPage = $('#perPage').val();
let token = $('meta[name="_token"]').attr('content');
let keyword = '';
let ajaxReq = null;
// let formData = '';
let formData = $('#form-search').serialize();

$(document).ready(function() {
    if (url != undefined) {
        pagination();
    }
});

$(".select2").select2();

$(".select2_tag").select2({
    tags: true,
    closeOnSelect: false
});

$(".__from_date").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    startDate: '-0d',
}).on('changeDate', function (selected) {
    $('.__to_date').val('');
    var minDate = new Date(selected.date.valueOf());
    $('.__to_date').datepicker('setStartDate', minDate);
});

$(".__to_date").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    startDate: '-0d',
}).on('changeDate', function (selected) {
    // var maxDate = new Date(selected.date.valueOf());
    // $('.__from_date').datepicker('setEndDate', maxDate);
});

// initSample();

$('.datepicker_future').datepicker({
    format: 'dd-mm-yyyy',
    changeYear: true,
    changeMonth: true,
    minDate: 0,
    autoclose: true,
});

$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    changeYear: true,
    changeMonth: true,
    autoclose: true,
});

$(".__from_date2").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    // minDate: 0,
    endDate: '-0d',
}).on('changeDate', function (selected) {
    
    let fromdate = new Date($('.__from_date2').val()).valueOf();
    let todate = new Date($('.__to_date2').val()).valueOf();
     
    if(todate < fromdate) {
        alert('TO DATE cant less than FROM DATE');
    }

    var minDate = new Date(selected.date.valueOf());
    $('.__to_date2').datepicker('setStartDate', minDate);
    $('.__to_date2').datepicker('setEndDate', new Date());
});

$(".__to_date2").datepicker({
    format: 'dd-mm-yyyy',
    startDate: '-0d',
    endDate: '-0d',
    autoclose: true,
}).on('changeDate', function (selected) {
    // var maxDate = new Date(selected.date.valueOf());
    // $('.__from_date').datepicker('setEndDate', maxDate);
});

// Timepicker
$('.timepicker').timepicker({
    'format': 'd/m/Y H:i',
    'minDate': -0,
    'closeOnDateSelect' : true,
    'validateOnBlur' : true,
    'minDateTime': new Date()
}); // date picker with time restriction  //
//Timepicker
// Time picker with time restriction start //


// Time picker with time restriction end //


$('body').on('change', '.__check_all', function() {
    if ($(this).is(':checked')) {
        $('.__check').prop('checked', true);
        $('.__check').parent().parent().addClass('bg-warning text-white');
    } else {
        $('.__check').prop('checked', false);
        $('.__check').parent().parent().removeClass('bg-warning text-white');
    }
});

$('body').on('change keyup', 'input', function() {
    $(this).parent().find('.error').slideUp('slow');
});

$('body').on('change', '.__check', function() {
    if ($(this).is(':checked')) {
        $(this).parent().parent().addClass('bg-warning text-white');
    } else {
        $(this).parent().parent().removeClass('bg-warning text-white');
    }
});

$('body').on('change', '.__status', function(e) {
    e.preventDefault();
    var option = { _token: token, _method: 'post' };
    var route = $(this).attr('data-route');

    $.ajax({
        type: 'post',
        url: route,
        data: option,
        success: function(data) {
            /*if (data.success && data.status == 201) {
             alert(data.message);
             // window.location.reload();
             }*/
        },
        error: function(data) {
            alert('An error occurred.');
            // console.log('An error occurred.');
        }
    });
});

$(document).ready(function() {
    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();
        url = $(this).attr('href');
        pagination();
        window.history.pushState('', '', url);
    });

    $('body').on('click', '#pagination th a', function(e) {
        e.preventDefault();
        sortEntity = $(this).attr('data-sortEntity');
        sortOrder = $(this).attr('data-sortOrder');
        pagination();
        window.history.pushState('', '', url);
    });

    $('body').on('change', '#perPage', function() {
        perPage = $(this).val();
        url = pageContainer.data('url');
        pagination();
        window.history.pushState('', '', url);
    });

    $('body').on('keyup', 'input[name=keyword]', function() {
        if (ajaxReq != null) ajaxReq.abort();
        keyword = $(this).val();
        pagination();
    });

    $('body').on('change', '#form-search select', function(e) {
        e.preventDefault();
        $('#form-search').find(".error").remove();
        formData = $('#form-search').serialize();
        url = pageContainer.data('url');
        window.history.pushState('', '', url);
        pagination();
    });

    $('#form-search').submit(function(e) {
        e.preventDefault();
        $('#form-search').find(".error").remove();
        formData = $(this).serialize();
        url = pageContainer.data('url');
        window.history.pushState('', '', url);
        pagination();
    });

    $('#ajax-submit').submit(function(e) {
        e.preventDefault();
        try {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        } catch (e) { 
            // print error
        }

        $('#ajax-submit').find(".error").remove();
        $('#ajax-submit').find(".border-red").removeClass('border-red');
        var form = $('#ajax-submit');

        $('.__password_div').find('input').attr('required', false);
        $('.__password_div').hide();

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            beforeSend: function() {
                showLoader();
            },
            success: function(data) {
                if (data.success) {
                    $(this).find("button[type='submit']").prop('disabled', true);
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
                } else {
                    if (data.status == 206) {
                        hideLoader();
                        $.each(data.message, function(i, v) {
                            var error = '<div class="error">' + v + '</div>';
                            var split = i.split('.');
                            if (split[2]) {
                                var ind = split[0] + '[' + split[1] + ']' + '[' + split[2] + ']';
                                form.find("[name='" + ind + "']").addClass('border-red');
                                form.find("[name='" + ind + "']").parent().append(error);
                            } else if (split[1]) {
                                var ind = split[0] + '[' + split[1] + ']';
                                form.find("[name='" + ind + "']").addClass('border-red');
                                form.find("[name='" + ind + "']").parent().append(error);
                            } else {
                                form.find("[name='" + i + "']").addClass('border-red');
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
                    else if (data.status == 205) {
                        hideLoader();
                        $('.__password_div').find('input').attr('required', true);
                        $('.__password_div').show();
                    }
                }
            },
            error: function(data) {
                console.log('An error occurred.');
            }
        });
    });
    
    $('#ajax-submit2').submit(function(e) {
        e.preventDefault();
        try {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        } catch (e) { 
            // print error
        }

        $('#ajax-submit2').find(".error").remove();
        $('#ajax-submit2').find(".border-red").removeClass('border-red');
        var form = $('#ajax-submit2');

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            beforeSend: function() {
                showLoader();
            },
            success: function(data) {
                if (data.success) {
                    $(this).find("button[type='submit']").prop('disabled', true);
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
                } else {
                    if (data.status == 206) {
                        hideLoader();
                        $.each(data.message, function(i, v) {
                            var error = '<div class="error">' + v + '</div>';
                            var split = i.split('.');
                            if (split[2]) {
                                var ind = split[0] + '[' + split[1] + ']' + '[' + split[2] + ']';
                                form.find("[name='" + ind + "']").addClass('border-red');
                                form.find("[name='" + ind + "']").parent().append(error);
                            } else if (split[1]) {
                                var ind = split[0] + '[' + split[1] + ']';
                                form.find("[name='" + ind + "']").addClass('border-red');
                                form.find("[name='" + ind + "']").parent().append(error);
                            } else {
                                form.find("[name='" + i + "']").addClass('border-red');
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
            },
            error: function(data) {
                console.log('An error occurred.');
            }
        });
    });

    $('#login-submit').submit(function(e) {
        e.preventDefault();
        try {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        } catch (e) {
            // print error
        }

        $('#login-submit').find(".error").remove();
        $('#login-submit').find(".border-red").removeClass('border-red');
        $('#login-submit').find('.207_error').html('').hide();
        var form = $('#login-submit');

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            beforeSend: function() {
                showLoader();
            },
            success: function(data) {
                if (data.success) {
                    $(this).find("button[type='submit']").prop('disabled', true);
                    hideLoader();

                    if (data.extra.redirect) {
                        window.location.href = data.extra.redirect;
                    }
                }
                else {
                    if (data.status == 206) {
                        hideLoader();
                        $.each(data.message, function(i, v) {
                            var error = '<div class="error">' + v + '</div>';
                            var split = i.split('.');
                            if (split[2]) {
                                var ind = split[0] + '[' + split[1] + ']' + '[' + split[2] + ']';
                                form.find("[name='" + ind + "']").addClass('border-red');
                                form.find("[name='" + ind + "']").parent().append(error);
                            } else if (split[1]) {
                                var ind = split[0] + '[' + split[1] + ']';
                                form.find("[name='" + ind + "']").addClass('border-red');
                                form.find("[name='" + ind + "']").parent().append(error);
                            } else {
                                form.find("[name='" + i + "']").addClass('border-red');
                                form.find("[name='" + i + "']").parent().append(error);
                            }
                        });
                    } else if (data.status == 207) {
                        hideLoader();

                        if (data.message != '') {
                            $('.207_login_error').html(data.message).show();
                        }
                    }
                }
            },
            error: function(data) {
                console.log('An error occurred.');
            }
        });
    });

    $('#register-submit').submit(function(e) {
        e.preventDefault();
        try {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        } catch (e) {
            // print error
        }

        $('#register-submit').find(".error").remove();
        $('#register-submit').find(".border-red").removeClass('border-red');
        $('#register-submit').find('.207_error').html('').hide();
        var form = $('#register-submit');

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            beforeSend: function() {
                showLoader();
            },
            success: function(data) {
                if (data.success) {
                    $(this).find("button[type='submit']").prop('disabled', true);
                    hideLoader();

                    if (data.extra.redirect) {
                        window.location.href = data.extra.redirect;
                    }
                }
                else {
                    if (data.status == 206) {
                        hideLoader();
                        $.each(data.message, function(i, v) {
                            var error = '<div class="error">' + v + '</div>';
                            var split = i.split('.');
                            if (split[2]) {
                                var ind = split[0] + '[' + split[1] + ']' + '[' + split[2] + ']';
                                form.find("[name='" + ind + "']").addClass('border-red');
                                form.find("[name='" + ind + "']").parent().append(error);
                            } else if (split[1]) {
                                var ind = split[0] + '[' + split[1] + ']';
                                form.find("[name='" + ind + "']").addClass('border-red');
                                form.find("[name='" + ind + "']").parent().append(error);
                            } else {
                                form.find("[name='" + i + "']").addClass('border-red');
                                form.find("[name='" + i + "']").parent().append(error);
                            }
                        });
                    } else if (data.status == 207) {
                        hideLoader();

                        if (data.message != '') {
                            $('.207_register_error').html(data.message).show();
                        }
                    }
                }
            },
            error: function(data) {
                console.log('An error occurred.');
            }
        });
    });

    $('body').on('click', '.__drop', function(e) {
        e.preventDefault();
        var conf = confirm('Are you sure ?');
        if (conf) {
            var option = { _token: token, _method: 'delete' };
            var route = $(this).attr('data-url');
            showLoader();
            $.ajax({
                type: 'post',
                url: route,
                data: option,
                success: function(data) {
                    if (data.success && data.status == 201) {
                        showLoader();
                        alert(data.message);
                        window.location.reload();
                    }
                },
                error: function(data) {
                    console.log('An error occurred.');
                }
            });
        }
    });

    let cancel_route = '';
    $('body').on('click', '.__cancel', function(e) {
        e.preventDefault();
        cancel_route = $(this).attr('data-url');
        $('.__cancel_modal').modal('show');
    });

    $('body').on('click', '.__cancel_yes_btn', function(e) {
        $.ajax({
            type: 'get',
            url: cancel_route,
            data: {},
            success: function(data) {
                if (data.success && data.status == 201) {
                    // alert(data.message);
                    window.location.reload();
                }
            },
            error: function(data) {
                console.log('An error occurred.');
            }
        });
    });

    $('body').on('click', '.__toggle', function(e) {
        e.preventDefault();
        var conf = confirm('Are you sure to change status?');
        if (conf) {
            var option = { _token: token, _method: 'post' };
            var route = $(this).attr('data-route');
            showLoader();

            $.ajax({
                type: 'post',
                url: route,
                data: option,
                success: function(data) {
                    if (data.success && data.status == 201) {
                        showLoader();
                        alert(data.message);
                        window.location.reload();
                    }
                },
                error: function(data) {
                    console.log('An error occurred.');
                }
            });
        }
    });

    $('body').on('click', '.__dynamic', function(e) {
        e.preventDefault();

        var route = $(this).attr('href');
        var heading = $(this).attr('data-heading');
        showLoader();
        $.ajax({
            type: 'get',
            url: route,
            success: function(response) {
                $('.__dynamic_heading').html(heading);
                $('.__dynamic_detail').html(response);
                hideLoader();
                $('.__dynamic_modal').modal('show');
            },
            error: function(response) {
                console.log('An error occurred.');
            }
        });
    });

    $('body').on('click', '.__toggle_all', function() {
        if ($('.__check:checked').length <= 0) {
            alert('Please select alteast one status');
            return false;
        }

        var ids = [];
        $(".__check:checked").each(function() {
            ids.push($(this).val());
        });

        var option = {
            _token: token,
            _method: 'post',
            ids: ids
        };
        var route = $(this).attr('data-route');
        showLoader();

        $.ajax({
            type: 'post',
            url: route,
            data: option,
            success: function(data) {
                if (data.success && data.status == 201) {
                    hideLoader();
                    alert(data.message);
                    // window.location.reload();

                    pagination();
                }
            },
            error: function(data) {
                hideLoader();
                pagination();
                console.log('An error occurred.');
            }
        });
    });

    $('body').on('click', '.__toggle_all_cancel', function() {
        if ($('.__check:checked').length <= 0) {
            alert('Please select alteast one status');
            return false;
        }

        if($('.__cancel_reason').val() == '') {
            alert('Please select cancel reason');
            return false;
        }

        var ids = [];
        $(".__check:checked").each(function() {
            ids.push($(this).val());
        });

        var cancel_reason = $('.__cancel_reason').val();

        var option = {
            _token: token,
            _method: 'post',
            ids: ids,
            cancel_reason: cancel_reason
        };
        var route = $(this).attr('data-route');
        showLoader();

        $.ajax({
            type: 'post',
            url: route,
            data: option,
            success: function(data) {
                if (data.success && data.status == 201) {
                    hideLoader();
                    alert(data.message);
                    // window.location.reload();

                    pagination();
                }
            },
            error: function(data) {
                hideLoader();
                pagination();
                console.log('An error occurred.');
            }
        });
    });

    $('body').on('click', '.__add_row', function(e) {
        e.preventDefault();
        var source = $(this).data('source');
        var target = $(this).data('target');
        var html = $(source).find('tbody').html();
        $(target).append(html).show();
    });

    $('body').on('click', '.__remove_row', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    });
});

function ajaxFire(route, data = {}, callback) {
    data._token = token;
    $.ajax({
        type: 'POST',
        url: route,
        data: data,
        beforeSend: function() {
            showLoader();
        },
        success: function(response) {
            hideLoader();

            if(typeof callback === "function") {
                callback(response);
            }
        },
        error: function(data) {
            console.log('An error occurred.');
        }
    });
}

function ajaxFireWithLoader(route, data, callback) {
    data._token = token;
    $.ajax({
        type: 'POST',
        url: route,
        data: data,
        success: function(response) {
            if(typeof callback === "function") {
                callback(response);
            }
        },
        error: function(data) {
            console.log('An error occurred.');
        }
    });
}

function pagination() {
    $('#form-search').find(".error").remove();

    let formData = $('#form-search').serialize();
    if (formData != '') {
        formData = formData + '&';
    }

    var option = formData + 'sortEntity=' + sortEntity +
        '&sortOrder=' + sortOrder +
        '&perPage=' + perPage +
        '&keyword=' + keyword +
        '&_token=' + token +
        '&tab=' + $('input[name=tab]').val();

    ajaxReq = $.ajax({
        type: 'GET',
        url: url,
        data: option,
        beforeSend: function() {
            showLoader();
        },
        success: function(data) {
            ajaxReq = null;
            if ((data.success == false) && (data.status == 206)) {
                $.each(data.message, function(i, v) {
                    var error = '<div class="error">' + v + '</div>';
                    $('#form-search').find("[name='" + i + "']").parent().append(error);
                });
            } else {
                pageContainer.html(data);
            }
            hideLoader();
        },
        error: function(data) {
            console.log('An error occurred.');
        }
    });
}

function showLoader() {
    $('.loading').show();
}

function hideLoader() {
    $('.loading').hide();
}

function toggleDetail(reference) {
    $('body').find(reference).toggleClass('hidden');
}

function slugify(string) {
    const a = 'àáâäæãåāăąçćčđďèéêëēėęěğǵḧîïíīįìłḿñńǹňôöòóœøōõőṕŕřßśšşșťțûüùúūǘůűųẃẍÿýžźż·/_,:;'
    const b = 'aaaaaaaaaacccddeeeeeeeegghiiiiiilmnnnnoooooooooprrsssssttuuuuuuuuuwxyyzzz------'
    const p = new RegExp(a.split('').join('|'), 'g')

    return string.toString().toLowerCase()
        .replace(/\s+/g, '-') // Replace spaces with -
        .replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
        .replace(/&/g, '-and-') // Replace & with 'and'
        .replace(/[^\w\-]+/g, '') // Remove all non-word characters
        .replace(/\-\-+/g, '-') // Replace multiple - with single -
        .replace(/^-+/, '') // Trim - from start of text
        .replace(/-+$/, '') // Trim - from end of text
}

function select2Change(target, route, data) {
    $(target).html('');
    $(target).select2();

    data._token = token;
    $.ajax({
        type: 'POST',
        url: route,
        data: data,
        beforeSend: function() {
            showLoader();
        },
        success: function(data) {
            if (data.success) {
                hideLoader();

                $(target).html(data.options);
                $(target).select2();
            }
        },
        error: function(data) {
            console.log('An error occurred.');
        }
    });
}

$('body').on('change', '.__country', function(e) {
    var value = $(this).val();
    if($('.__state').length > 0) {
        if(value != '') {
            let route = $(this).attr('data-route');
            let data = {country_id: value};
            select2Change('.__state', route, data);
        }
    }
});

$('body').on('change', '.__state', function(e) {
    var value = $(this).val();
    var countryValue = $('.__country').val();
    if($('.__city').length > 0) {
        if(value != '') {
            let route = $(this).attr('data-route');
            let data = {country_id: countryValue, state_id: value};
            select2Change('.__city', route, data);
        }
    }
});

// active link open code start
var activeurl = window.location;
var menu_selector = $('#navbar_menu a[href="'+activeurl+'"]');
if(menu_selector.parent('li').parent('ul').parent('li').parent('ul')) {
    menu_selector.parent('li').parent('ul').parent('li').parent('ul').parent('li').addClass('active in');
    menu_selector.parent('li').parent('ul').parent('li').parent('ul').addClass('in');
}

if(menu_selector.parent('li').parent('ul').parent('li')) {
    menu_selector.parent('li').parent('ul').parent('li').addClass('active');
    menu_selector.parent('li').parent('ul').addClass('in');
}
if(menu_selector) {
    menu_selector.parent('li').addClass('active');
}
// active link open code end