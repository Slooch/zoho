<?php

namespace App\Http\Controllers;

use Asciisd\Zoho\ZohoManager;
use com\zoho\crm\api\record\Accounts;
use com\zoho\crm\api\record\Record;
use Illuminate\Http\Request;

class ZohoCrmAccountController extends Controller
{
    //

    public function addNewAccount(Request $request)
    {
        $validatedData = $request->validate([
            'Account_Name' => ['required', 'max:50'],
            'Website' => ['required', 'regex:/\b(?:(?:https?):\/\/|www\.)?[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'Phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
        ]); // array(3)
        $account = ZohoManager::useModule('Accounts');
        // initiating a new empty instance of leads

        return response()->json((object) $account->create([
            'Account_Name' => $validatedData['Account_Name'],
            'Website' => $validatedData['Website'],
            'Phone' => $validatedData['Phone'],
        ]));
    }

    public function allAccounts(Request $request)
    {
        $accounts = [];
        $accountsRecords = ZohoManager::make('Accounts')->getRecords(fields: ['id', 'Account_Name', 'Website', 'Phone' ]);
        foreach($accountsRecords as $account) {
           $accounts[] = $account->getKeyValues();
        }
        return response()->json($accounts);
    }

    public function updateAccount(Request $request)
    {
        $validatedData = $request->validate([

            'id' => ['required', 'min:10', 'max:50'],
            'Account_Name' => ['required', 'max:50'],
            'Website' => ['regex:/\b(?:(?:https?):\/\/|www\.)?[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'Phone' => ['regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
        ]);

        $accounts = ZohoManager::useModule('Accounts');
        $record = new Record();
        $record->setId($validatedData['id']);

        //$account = $accounts->getRecord($validatedData['id']);
        $record->addFieldValue(Accounts::AccountName(), $validatedData['Account_Name']);
        $record->addFieldValue(Accounts::Website(), $validatedData['Website']);
        $record->addFieldValue(Accounts::Phone(), $validatedData['Phone']);

        return response()->json((object) $accounts->update($record));
    }

    public function deleteAccount(Request $request)
    {
        $validatedData = $request->validate([

            'id' => ['required', 'min:10', 'max:50'],
            'Account_Name' => ['required', 'max:50'],
        ]);
        $accounts = ZohoManager::useModule('Accounts');

        return response()->json((object) $accounts->delete([$validatedData['id']]));
    }
}
