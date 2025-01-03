<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Layout\Content;

class ExchangeRateController extends Controller
{
    /**
     * Show all exchange rates and allow editing.
     *
     * @return Content
     */
    public function index()
    {
        // Retrieve all exchange rates
        $exchangeRates = ExchangeRate::all();

        // Return the view with the exchange rates
        return Admin::content(function (Content $content) use ($exchangeRates) {
            $content->header('Exchange Rate');

            $content->body(view('admin.exchange', ['exchangeRates' => $exchangeRates]));
        });
    }

    /**
     * Update the specified exchange rate in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'rate' => 'required|numeric|min:1', // Ensure a positive decimal number
        ]);

        // If validation fails, redirect back to the form with errors
        if ($validator->fails()) {
            return redirect()->route('admin.exchange')
                ->withErrors($validator)
                ->withInput();
        }

        // Retrieve the exchange rate record by ID
        $exchangeRate = ExchangeRate::findOrFail($id);

        // Update the exchange rate with the validated data
        $exchangeRate->currency_from = 'USD';
        $exchangeRate->currency_to = "LKR";
        $exchangeRate->rate = $request->rate;

        // Save the changes to the database
        $exchangeRate->save();

        // Flash a success message and redirect back to the index page
        return redirect()->back();
    }
}
