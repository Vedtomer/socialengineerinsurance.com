<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;

class WebsiteController extends Controller
{
    private function setSEO($title, $description)
    {
        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl(request()->url());
    }

    public function home()
    {
        $this->setSEO(
            'Social Engineer Insurance | E-Rikshaw Insurance',
            'Specialists in E-Rikshaw Insurance and All Types of Insurance Services, including Health and Motor Vehicle Insurance'
        );
        $dataWfPage = '65b60c5eef338f6b2401686d';
        return view('pages.website.home',compact('dataWfPage'));
    }

    public function eRickshawInsurance()
    {
        $this->setSEO(
            'E-Rickshaw Insurance Services | Social Engineer Insurance',
            'Comprehensive E-Rickshaw insurance coverage with the best premium rates and quick claim settlement'
        );
        return view('pages.website.e_rickshaw_insurance');
    }

    public function insurance()
    {
        $this->setSEO(
            'Insurance Services | Social Engineer Insurance',
            'Complete range of insurance services including vehicle, health, and property insurance'
        );
        return view('pages.website.insurance');
    }

    public function healthInsurance()
    {
        $this->setSEO(
            'Health Insurance Plans | Social Engineer Insurance',
            'Affordable health insurance plans with comprehensive coverage for individuals and families'
        );
        return view('pages.website.health_insurance');
    }

    public function twoWheelerInsurance()
    {
        $this->setSEO(
            'Two Wheeler Insurance | Social Engineer Insurance',
            'Comprehensive two-wheeler insurance with quick claim settlement and best premium rates'
        );
        return view('pages.website.two_wheeler_insurance');
    }

    public function homeInsurance()
    {
        $this->setSEO(
            'Home Insurance | Social Engineer Insurance',
            'Protect your home with comprehensive insurance coverage against natural disasters and accidents'
        );
        return view('pages.website.home_insurance');
    }

    public function privateCarInsurance()
    {
        $this->setSEO(
            'Private Car Insurance | Social Engineer Insurance',
            'Comprehensive car insurance with quick claim settlement and competitive premium rates'
        );
        return view('pages.website.private_car_insurance');
    }

    public function about()
    {
        $this->setSEO(
            'About Us | Social Engineer Insurance',
            'Learn about our mission to provide the best insurance services with customer satisfaction'
        );

        $dataWfPage = '65c44d67f63a923b7d56c38a';
        return view('pages.website.about', compact('dataWfPage'));
    }

    public function contact()
    {
        $this->setSEO(
            'Contact Us | Social Engineer Insurance',
            'Get in touch with our insurance experts for the best guidance and support'
        );
        $dataWfPage = '65cda451a80f1552a7289505';
        return view('pages.website.contact',compact('dataWfPage'));
    }
}
