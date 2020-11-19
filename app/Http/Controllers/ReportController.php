<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderDetail;
use App\Product;
use App\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function procurementReport(Request $request)
    {
        $result = null;
        if($request->ajax()) {
            $inputs = $request->all();
            $validation = validator($inputs, [
                'from_date' => 'required|date',
                'to_date' => 'required|date'
            ]);

            if($validation->fails()) {
                return jsonResponse(false, 206, $validation->getMessageBag());
            }

            $result = (new OrderDetail)->procurementReport($inputs);
            return view('report.procurement.pagination', compact('result'));
        }
        return view('report.procurement.index', compact('result'));
    }

    public function tabularReport(Request $request)
    {
        $totalOrders = null;
        $totalProducts = null;
        $totalCashPayment = null;
        $totalOnlinePayment = null;
        $totalOrderAmount = null;
        $result = null;

        $inputs = $request->all();

        if($request->ajax()) {
            $result = true;
            
            $validation = validator($inputs, [
                'from_date' => 'required|date',
                'to_date' => 'required|date'
            ]);

            if($validation->fails()) {
                return jsonResponse(false, 206, $validation->getMessageBag());
            }

            $totalOrders = (new Order)->totalOrders($inputs);
            $totalProducts = (new OrderDetail)->totalProducts($inputs);
            $totalCashPayment = (new Order)->cashPaymentAmount($inputs);
            $totalOnlinePayment = (new Order)->onlinePaymentAmount($inputs);
            $totalOrderAmount = (new Order)->totalOrdersAmount($inputs);

            return view('report.tabular.pagination', compact(
                'result',
                'totalOrders',
                'totalProducts',
                'totalCashPayment',
                'totalOnlinePayment',
                'totalOrderAmount',
                'inputs'
            ));
        }
        return view('report.tabular.index', compact(
            'result',
            'totalOrders',
            'totalProducts',
            'totalCashPayment',
            'totalOnlinePayment',
            'totalOrderAmount',
            'inputs'
        ));
    }

    public function downloadReport(Request $request)
    {
        $totalDownloads = null;
        $totalClientRegistered = null;
        $totalOrders = null;
        $totalOrderAmount = null;
        $result = null;
        $inputs = $request->all();

        if($request->ajax()) {
            $result = true;

            $validation = validator($inputs, [
                'from_date' => 'required|date',
                'to_date' => 'required|date'
            ]);

            if($validation->fails()) {
                return jsonResponse(false, 206, $validation->getMessageBag());
            }

            $totalDownloads = (new User)->totalClientRegistered($inputs);
            $totalClientRegistered = (new User)->totalAppDownload($inputs);
            $totalOrders = (new Order)->totalOrders($inputs);
            $totalOrderAmount = (new Order)->totalOrdersAmount($inputs);

            return view('report.download.pagination', compact(
                'result',
                'totalDownloads',
                'totalClientRegistered',
                'totalOrders',
                'totalOrderAmount',
                'inputs'
            ));
        }
        return view('report.download.index', compact(
            'result',
            'totalDownloads',
            'totalClientRegistered',
            'totalOrders',
            'totalOrderAmount',
            'inputs'
        ));
    }

    public function repeatOrderReport(Request $request)
    {
        $result = null;
        $inputs = $request->all();

        if($request->ajax()) {
            $validation = validator($inputs, [
                'from_date' => 'required|date',
                'to_date' => 'required|date'
            ]);

            if($validation->fails()) {
                return jsonResponse(false, 206, $validation->getMessageBag());
            }

            $result = (new Order)->repeatOrderReport($inputs);
            return view('report.order.pagination', compact('result', 'inputs'));
        }
        return view('report.order.index', compact('result', 'inputs'));
    }

    public function loginReport(Request $request)
    {
        $result = null;
        if($request->ajax()) {
            $inputs = $request->all();
            $validation = validator($inputs, [
                'from_date' => 'required|date',
                'to_date' => 'required|date'
            ]);

            if($validation->fails()) {
                return jsonResponse(false, 206, $validation->getMessageBag());
            }

            $result = (new User)->loginReport($inputs);
            return view('report.login.pagination', compact('result'));
        }
        return view('report.login.index', compact('result'));
    }
}