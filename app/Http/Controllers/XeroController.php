<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Xero_contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Webfox\Xero\OauthCredentialManager;

class XeroController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application|mixed
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkXeroAuth(OauthCredentialManager $xeroCredentials){
        try {
            if ($xeroCredentials->exists()) {
                $xero             = resolve(\XeroAPI\XeroPHP\Api\AccountingApi::class);
            }
            return $xero;
        } catch (\throwable $e) {
            return $e->getMessage();
        }
    }

    public function index(Request $request, OauthCredentialManager $xeroCredentials)
    {
        try {
                $xero = $this->checkXeroAuth($xeroCredentials);
                $organisationName = $xero->getOrganisations($xeroCredentials->getTenantId())->getOrganisations()[0]->getName();
                $user             = $xeroCredentials->getUser();
                $username         = "{$user['given_name']} {$user['family_name']} ({$user['username']})";
        } catch (\throwable $e) {
            $error = $e->getMessage();
        }

        return view('xero', [
            'connected'        => $xeroCredentials->exists(),
            'error'            => $error ?? null,
            'organisationName' => $organisationName ?? null,
            'username'         => $username ?? null
        ]);
    }

    public function getItems(Request $request, OauthCredentialManager $xeroCredentials)
    {
//        dd(config('xero.oauth.client_id'));
        try {
            $xero = $this->checkXeroAuth($xeroCredentials);
            $items = $xero->getItems($xeroCredentials->getTenantId());
        } catch (\throwable $e) {
            $error = $e->getMessage();
        }
        dd($items->getItems());

        return view('xero');
    }

    public function getContacts(Request $request, OauthCredentialManager $xeroCredentials)
    {
//        dd(config('xero.oauth.client_id'));
        try {
            $xero = $this->checkXeroAuth($xeroCredentials);
            $items = $xero->getContacts($xeroCredentials->getTenantId())->getContacts();
        } catch (\throwable $e) {
            $error = $e->getMessage();
        }
//        dd($items[34]);

        foreach ($items as $item) {
            $existingContact = Xero_contact::where('contactid', $item['contact_id'])->first();

            if ($item['is_supplier']) { $issupplier = 0; } else { $issupplier = 1; }
            if ($item['is_customer']) { $iscustomer = 0; } else { $iscustomer = 1; }

            preg_match('/\d+/', $item['updated_date_utc'], $matches);
            $timestamp = intval($matches[0]);
            $updateddateutc = date('Y-m-d H:i:s', $timestamp / 1000); // Împărțim la 1000 deoarece timestamp-ul este în milisecunde

            if (!$existingContact) {
                Xero_contact::create([
                        'contactid' => $item['contact_id'],
                        'contactnumber' => $item['contact_number'],
                        'accountnumber' => $item['account_number'],
                        'contactstatus' => $item['contact_status'],
                        'name' => $item['name'],
                        'firstname' => $item['first_name'],
                        'lastname' => $item['last_name'],
                        'emailaddress' => $item['email_address'],
                        'skypeusername' => null,
                        'bankaccountdetails' => $item['bank_account_details'],
                        'companynumber' => $item['company_number'],
                        'taxnumber' => $item['tax_number'],
                        'accountsreceivabletaxtype' => $item['accounts_receivable_tax_type'],
                        'addresses' => 0,
                        'phones' => 0,
                        'issupplier' => $issupplier,
                        'iscustomer' => $iscustomer,
                        'defaultcurrency' => $item['default_currency'],
                        'updateddateutc' => $updateddateutc,
                    ]
                );
            } else {
                $existingContact->update([
                    'contactnumber' => $item['contact_number'],
                    'accountnumber' => $item['account_number'],
                    'contactstatus' => $item['contact_status'],
                    'name' => $item['name'],
                    'firstname' => $item['first_name'],
                    'lastname' => $item['last_name'],
                    'emailaddress' => $item['email_address'],
                    'skypeusername' => null,
                    'bankaccountdetails' => $item['bank_account_details'],
                    'companynumber' => $item['company_number'],
                    'taxnumber' => $item['tax_number'],
                    'accountsreceivabletaxtype' => $item['accounts_receivable_tax_type'],
                    'addresses' => 0,
                    'phones' => 0,
                    'issupplier' => $issupplier,
                    'iscustomer' => $iscustomer,
                    'defaultcurrency' => $item['default_currency'],
                    'updateddateutc' => $updateddateutc,
                ]);
            }
        }
        echo 'contactele au fos incarcate in Tabel "XERO_CONTACTS"';
        echo '<br>';
//        dd(Xero_contact::get());

        return null;
    }

}
