<?php

namespace Credibility\LaravelLocaleze;

use Illuminate\Foundation\Application;
use Illuminate\Database\DatabaseManager;

class Localeze {

    const TAXONOMY_QUERY_ELEMENT_KEY = 2935;
    const BUSINESS_POST_ELEMENT_KEY = 3700;
    const BUSINESS_CLOSE_ELEMENT_KEY = 3710;
    const SUBSCRIPTION_ADMINISTRATION_ELEMENT_KEY = 3712;
    const QUERY_DETAIL_ELEMENT_KEY = 3720;
    const QUERY_SUMMARY_ELEMENT_KEY = 3721;
    const CHECK_AVAILABILITY_ELEMENT_KEY = 3722;

    const PHONE_NUMBER_SERVICE_KEY = 1;
    const ZIP_CODE_SERVICE_KEY = 1393;
    const BUSINESS_NAME_SERVICE_KEY = 1396;
    const QUERY_FORMAT_SERVICE_KEY = 1501;
    const BUSINESS_INFORMATION_SERVICE_KEY = 1510;
    const BPMS_PERMANENT_BUSINESS_RECORD_SERVICE_KEY = 1511;
    const RECORD_ID_SERVICE_KEY = 1513;
    const LICENSE_INFORMATION_SERVICE_KEY = 1518;

    /** @var LocalezeRequester  */
    public $requester;

    /** @var Application  */
    public $app;

    /** @var DatabaseManager */
    public $db;

    /**
     * @param LocalezeRequester $requester
     * @param Application $app
     */
    public function __construct(LocalezeRequester $requester, Application $app)
    {
        $this->requester = $requester;
        $this->app = $app;
        $this->db = $app->make('db');
    }

    /**
     * Returns if the business is available, owned by you, or unavailable
     * @param LocalezeBusiness $business
     * @return string
     */
    public function checkAvailability(LocalezeBusiness $business)
    {
        $localezeResponse  = $this->processLocalezeRequest(
            [self::CHECK_AVAILABILITY_ELEMENT_KEY],
            [self::BUSINESS_INFORMATION_SERVICE_KEY => $business->toXML()]);

        $status = "UNAVAILABLE";

        switch($localezeResponse->errorCode) {
            case 1:
                $status = "AVAILABLE";
                break;
            case 2:
                $status = "OWNED";
                break;
        }

        return $status;
    }

    /**
     * @param LocalezeBusiness $business
     * @return LocalezeResponse
     */
    public function businessPost(LocalezeBusiness $business)
    {
        return $this->processLocalezeRequest(
            [self::BUSINESS_POST_ELEMENT_KEY],
            [self::BUSINESS_INFORMATION_SERVICE_KEY => $business->toXML()]
        );
    }

    /**
     * @param string $category
     * @return LocalezeResponse
     */
    public function taxonomyQuery($category = "ACTIVEHEADINGS")
    {
        return $this->processLocalezeRequest(
            [self::TAXONOMY_QUERY_ELEMENT_KEY],
            [self::QUERY_FORMAT_SERVICE_KEY => $category]
        );
    }

    /**
     * @param LocalezeBusiness $business
     * @return LocalezeResponse
     */

    //does not reopen business if called again
    public function businessClose(LocalezeBusiness $business)
    {
        return $this->processLocalezeRequest(
            [self::BUSINESS_CLOSE_ELEMENT_KEY],
            [self::BUSINESS_INFORMATION_SERVICE_KEY => $business->toXML()]
        );
    }

    /**
     * @param LocalezeBusiness $business
     * @return LocalezeResponse
     */
    public function subscriptionAdministration(LocalezeBusiness $business)
    {
        //License has an ID, a status (ACTIVE or DISABLED), and a renewal method (1 - not renew, 2 - auto)
        return $this->processLocalezeRequest(
            [self::SUBSCRIPTION_ADMINISTRATION_ELEMENT_KEY],
            [
                self::BUSINESS_INFORMATION_SERVICE_KEY => $business->toXML(),
                self::LICENSE_INFORMATION_SERVICE_KEY => "LICENSE INFO"
            ]
        );
    }

    public function summaryOfBusinesses(LocalezeFilter $filter)
    {
        return $this->processLocalezeRequest(
            [self::QUERY_SUMMARY_ELEMENT_KEY],
            [self::RECORD_ID_SERVICE_KEY => $filter->toXML()]
        );

    }

    /**
     * @param $phone
     * @return LocalezeResponse
     */
    public function phoneSearch($phone)
    {
        return $this->search([
            self::PHONE_NUMBER_SERVICE_KEY => $phone
        ]);
    }

    /**
     * @param $businessName
     * @param $zip
     * @return LocalezeResponse
     */
    public function businessAndZipSearch($businessName, $zip)
    {
        return $this->search([
            self::BUSINESS_NAME_SERVICE_KEY => $businessName,
            self::ZIP_CODE_SERVICE_KEY => $zip
        ]);
    }

    /**
     * @param $id
     * @return LocalezeResponse
     */
    public function recordIdSearch($id)
    {
        return $this->search([
            self::BPMS_PERMANENT_BUSINESS_RECORD_SERVICE_KEY => $id
        ]);
    }

    /**
     * @return LocalezeBusiness
     */
    public function createBusiness()
    {
        return new LocalezeBusiness();
    }

    /**
     * @return LocalezeFilter
     */
    public function createFilter()
    {
        return new LocalezeFilter();
    }

    /**
     * @param $sic
     * @return bool
     */
    public function getCategoryFromSic($sic)
    {
        $category = $this->db->select('
            SELECT category_name
            FROM localeze_categories
            WHERE sic_code = ?',
            [$sic]
        );

        return !empty($category) ? $category[0]->category_name : false;
    }

    /**
     * @param $category
     * return bool
     */
    public function isValidCategory($category)
    {
        $result = $this->db->select('
            SELECT category_name
            FROM localeze_categories
            WHERE category_name = ?',
            [$category]
        );

        return !empty($result);
    }

    private function processLocalezeRequest($elementKeys, $serviceKeys)
    {
        $request = new LocalezeRequest($elementKeys);

        foreach($serviceKeys as $key => $value){
            $request->addServiceKey($key, $value);
        }

        return $this->requester->process($request);
    }

    /**
     * @return LocalezeResponse
     */
    private function search(array $serviceKeys)
    {
        return $this->processLocalezeRequest(
            [self::QUERY_DETAIL_ELEMENT_KEY],
            $serviceKeys
        );
    }
}