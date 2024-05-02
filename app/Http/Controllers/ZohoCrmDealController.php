<?php

namespace App\Http\Controllers;

use Asciisd\Zoho\ZohoManager;
use com\zoho\crm\api\record\Deals;
use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\util\Choice;
use Illuminate\Http\Request;

class ZohoCrmDealController extends Controller
{
    //
    public function allDeals(Request $request)
    {
        $deals = [];
        $dealsRecords = ZohoManager::make('Deals')->getRecords(fields: ['Account_Name', 'Deal_Name', 'Stage', 'Amount'. 'Closing_Date', 'id' ]);
        foreach($dealsRecords as $deal) {
            $records = [];
            foreach($deal->getKeyValues() as $key => $value) {
                if(is_object($value)) {
                    if($value instanceof Record) {
                        $records[$key]['name'] = $value->getName();
                        $records[$key]['id'] = $value->getId();
                    }else {
                        $records[$key] = $value->getValue();
                    }
                } else {
                    $records[$key] = $value;
                }
            }
            $deals[] = $records;
        }
        return response()->json($deals);
    }

    public function addDeal(Request $request)
    {
        $validatedData = $request->validate([
            'Account_Name' => ['required', 'array'],
            'Deal_Name' => ['required', 'max:100'],
            'Stage' => ['required', 'max:100'],
            'Closing_Date' => ['required', 'max:50'],
            'Amount' => ['required', 'max:50'],
        ]);
        $deals = ZohoManager::useModule('Deals');
        $accounts = ZohoManager::useModule('Accounts');
        $account = $accounts->getRecord($validatedData['Account_Name']['id']);
        $stage = new Choice($validatedData['Stage']);
        $deals->create([
            'Account_Name' => $account,
            'Deal_Name' => $validatedData['Deal_Name'],
            'Stage' => $stage,
        ]);

        return response()->json((object) ['success'=>'Deal registered successfully!']);
    }

    public function updateDeal(Request $request)
    {
        $validatedData = $request->validate([

            'id' => ['required', 'min:10', 'max:50'],
            'Deal_Name' => ['required', 'max:100'],
            'Stage' => ['required', 'max:100'],
        ]);

        $deals = ZohoManager::useModule('Deals');
        $record = $deals->getRecord($validatedData['id']);
        $record->addFieldValue(Deals::DealName(), $validatedData['Deal_Name']);
        $record->addFieldValue(Deals::Stage(), $validatedData['Stage']);

        return response()->json((object) $deals->update($record));
    }

    public function deleteDeal(Request $request)
    {
        $validatedData = $request->validate([

            'id' => ['required', 'min:10', 'max:50'],
        ]);
        $deals = ZohoManager::useModule('Deals');

        return response()->json((object) $deals->delete([$validatedData['id']]));
    }
}
