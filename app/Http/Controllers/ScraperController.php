<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Goutte\Client;
use App\CompanyDirector;
use App\CompanyIndustry;
use App\Company;
use App\CompanyLocation;

class ScraperController extends Controller
{

    /**
     * Get company information
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status_code' => 422,'message'=>'Invalid Url.'], 422);
        }

        $url = $request->get('url');

        $client = new Client();
        $crawler = $client->request('GET', $url);

       $company_information = [];

       $crawler->filter('#companyinformation > table > tbody > tr')->each(function ($node,$i) use (&$company_information) {

             $company_information['information'][$node->children()->first()->text()] = str_replace(array( '(', ')' ),'',preg_replace('/See.*/', '', $node->children()->siblings()->text()));
        
        });

       
       $crawler->filter('#contactdetails > table > tbody > tr')->each(function ($node,$i) use (&$company_information) {

             $company_information['contactdetails'][$node->children()->first()->text()] = preg_replace('/See.*/', '', $node->children()->siblings()->text());
        
        });


      
       $crawler->filter('#listingandannualcomplaincedetails > table > tbody > tr')->each(function ($node,$i) use (&$company_information) {

             $company_information['listing_complaince_details'][$node->children()->first()->text()] = preg_replace('/See.*/', '', $node->children()->siblings()->text());
        
        });

       
       $crawler->filter('#otherinformation > table > tbody > tr')->each(function ($node,$i) use (&$company_information) {

             $company_information['otherinformation'][$node->children()->first()->text()] = preg_replace('/See.*/', '', $node->children()->siblings()->text());
        
        });


      
       $crawler->filter('#otherinformation > #industryclassification > table > tbody > tr')->each(function ($node,$i) use (&$company_information) {

             $company_information['industryclassification'][$node->children()->first()->text()] = preg_replace('/See.*/', '', $node->children()->siblings()->text());
        
        });

      
       $crawler->filterXPath('//div[contains(@id, "directors")]')->filter('table > tbody > tr')->first()->each(function ($node,$i) use (&$company_information) {
             
             $headers = [];
             $node->children()->each(function ($node) use (&$headers){
                
               $headers[] = $node->text();

             });
            
             $node->siblings()->each(function ($node,$j) use (&$company_information,$headers){
                
               $node->children()->each(function ($node,$k) use (&$company_information,$j,$headers){
                
                        $company_information['directors'][$j][$headers[$k]] = $node->text();

                });


             });
             
        
        });

        
        $this->store($company_information);
        
        return response()->json(['status_code' => 200,'message'=>'Information Retrieved successfully.'], 200);
       
    }


    /**
     * Store company information
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store($data)
    {
        $company_information = Company::create([

            'cin' => $data['information']['Corporate Identification Number'], 'name' => $data['information']['Company Name'], 'status' => $data['information']['Company Status'],  
            'date_of_incorporation' => $data['information']['Age (Date of Incorporation)'], 'reg_number' => $data['information']['Registration Number'],  
            'category' => $data['information']['Company Category'],  'subcategory' => $data['information']['Company Subcategory'],  
            'class' => $data['information']['Class of Company'],  'roc_code' => $data['information']['ROC Code'],
            'members' => $data['information']['Number of Members (Applicable only in case of company without Share Capital)'],'email' => $data['contactdetails']['Email Address'],'registered_office' => $data['contactdetails']['Registered Office'],
            'listed' => $data['listing_complaince_details']['Whether listed or not'], 'last_agm_date' => empty($data['listing_complaince_details']['Date of Last AGM']) ? NULL : $data['listing_complaince_details']['Date of Last AGM'],
            'balance_sheet_date' => empty($data['listing_complaince_details']['Date of Balance sheet']) ? NULL : $data['listing_complaince_details']['Date of Balance sheet']
            
        ]);


        $company_id = $company_information->id;

        $company_location = CompanyLocation::create(['company_id' => $company_id,'state' => $data['otherinformation']['State'], 'district' =>$data['otherinformation']['District'],'city' =>$data['otherinformation']['City'],'pin' =>$data['otherinformation']['PIN']]);

        $company_industry = CompanyIndustry::create(['company_id' => $company_id,'section' => $data['industryclassification']['Section'], 'division' => $data['industryclassification']['Division'],'main_group' => $data['industryclassification']['Main Group'],'main_class' => $data['industryclassification']['Main Class']]);
        
        if(isset($data['directors'])){
            foreach($data['directors'] as $director){
        
                 CompanyDirector::create(['company_id' => $company_id, 'din' => $director['Director Identification Number'], 'name'  => $director['Name'],'designation'  => $director['Designation'],'date_of_appointment'  => $director['Date of Appointment']]);
        
            }
        }
        
    }
}
