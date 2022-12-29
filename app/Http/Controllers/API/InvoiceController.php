<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{
    public function index(Request $request){
        $limit = $request->query('limit') ? (int)$request->query('limit') : 10;
        $search = $request->query('search') ? $request->query('search') : '';
        $invoices = DB::table('sales_flat_invoice')->join('sales_flat_order', 'sales_flat_invoice.order_id', '=', 'sales_flat_order.entity_id')->join('sales_flat_shipment_track', 'sales_flat_order.entity_id', '=', 'sales_flat_shipment_track.order_id')->join('sales_flat_shipment_grid', 'sales_flat_order.entity_id', '=', 'sales_flat_shipment_grid.order_id')->select('sales_flat_invoice.entity_id','sales_flat_invoice.increment_id AS no_invoice','sales_flat_invoice.created_at', 'sales_flat_order.increment_id AS no_order','sales_flat_order.entity_id as order_id', 'sales_flat_order.customer_email', 'sales_flat_order.customer_firstname', 'sales_flat_order.customer_lastname', 'sales_flat_invoice.grand_total', 'sales_flat_order.shipping_description', 'sales_flat_order.status', 'sales_flat_shipment_track.track_number', 'sales_flat_shipment_grid.shipping_name')->orderBy('sales_flat_invoice.entity_id', 'desc');
        $result = null;
        if($search !== ""){
            $invoices = $invoices->where('sales_flat_order.increment_id', 'like', "%$search%")->orWhere('sales_flat_shipment_grid.shipping_name', 'like', "%$search%");
             
        }
        $result = $invoices -> paginate($limit);
        if($invoices){
            return ApiFormatter::createApi(200, $result);
        }else{
            return ApiFormatter::createApi(400, $result);
        }
    }
    // show
    public function show($id){
        try {
            $invoice = DB::table('sales_flat_invoice')->join('sales_flat_order', 'sales_flat_invoice.order_id', '=', 'sales_flat_order.entity_id')->join('sales_flat_shipment_track', 'sales_flat_order.entity_id', '=', 'sales_flat_shipment_track.order_id')->join('sales_flat_order_address', 'sales_flat_order.entity_id', '=', 'sales_flat_order_address.parent_id')->join('sales_flat_order_payment', 'sales_flat_order.entity_id', '=', 'sales_flat_order_payment.parent_id')->join('sales_flat_shipment_grid', 'sales_flat_order.entity_id', '=', 'sales_flat_shipment_grid.order_id')->select('sales_flat_invoice.entity_id','sales_flat_invoice.shipping_amount','sales_flat_invoice.subtotal','sales_flat_invoice.discount_amount','sales_flat_invoice.increment_id AS no_invoice','sales_flat_invoice.created_at', 'sales_flat_order.increment_id AS no_order', 'sales_flat_order.customer_email', 'sales_flat_order.customer_firstname', 'sales_flat_order.customer_lastname', 'sales_flat_invoice.grand_total', 'sales_flat_order.shipping_description', 'sales_flat_order.status', 'sales_flat_order_address.region', 'sales_flat_order_address.postcode', 'sales_flat_order_address.street', 'sales_flat_order_address.city', 'sales_flat_order_address.telephone', 'sales_flat_order_address.firstname AS address_firstname', 'sales_flat_order_address.lastname AS address_lastname', 'sales_flat_order_address.address_type', 'sales_flat_order_address.subdistrict','sales_flat_order_address.id_number','sales_flat_order_address.dob', 'sales_flat_order_payment.method AS method_payment', 'sales_flat_shipment_track.track_number', 'sales_flat_shipment_grid.total_qty', 'sales_flat_shipment_grid.shipping_name')->orderBy('sales_flat_invoice.entity_id', 'desc')->where('sales_flat_invoice.entity_id', $id)->first();
       
            $invoice_item = DB::table('sales_flat_invoice_item')->select('name', 'sku', 'qty', 'base_price', 'row_total')->where('parent_id', $invoice->entity_id)->get() ;
            // DD($invoice->no_invoice);
            if($invoice){
                $invoice -> detail_order = $invoice_item;
            }
            $result = ['data'=> $invoice];
            if($invoice){
                return ApiFormatter::createApi(200, $result);
            }else{
                return ApiFormatter::createApi(400, 'error');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ApiFormatter::createApi(500, ['message'=> 'server error']);
        }
        
    }
}
