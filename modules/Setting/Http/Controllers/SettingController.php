<?php

namespace Modules\Setting\Http\Controllers;

use App\AppHelpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Role\Model\Role;
use Modules\Setting\Model\AppointmentSetting;
use Modules\Setting\Model\CommissionRateSetting;
use Modules\Setting\Model\Language;
use Modules\Setting\Model\MailConfig;
use Modules\Setting\Model\Website;


class SettingController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request){
        return view("Setting::index");
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function emailConfig(Request $request){
        $post        = $request->post();
        $mail_config = MailConfig::getMailConfig();
        if($post){
            unset($post['_token']);
            foreach($post as $key => $value){
                $mail_config = MailConfig::where('key', $key)->first();
                if(!empty($mail_config)){
                    $mail_config->update(['value' => $value]);
                }else{
                    $mail_config        = new MailConfig();
                    $mail_config->key   = $key;
                    $mail_config->value = $value;
                    $mail_config->save();
                }
            }

            $request->session()->flash('success', 'Email Config updated successfully.');

            return redirect()->back();
        }

        return view("Setting::setting.email", compact('mail_config'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function langManagement(Request $request){
        $post = $request->post();
        $lang = new Language();

        if($post){
            $request->session()->flash('success', 'Feature not released yet, we will make it soon.');
            return redirect()->back();
        }

        return view("Setting::setting.language", compact('lang'));
    }

    /**
     * @return RedirectResponse
     */
    public function testSendMail(Request $request){
        $mail_to = 'phuchp.613@gmai.com';
        $subject = 'Test email';
        $title   = 'Test email function';
        $body    = 'We are testing email!';
        $send    = Helper::sendMail($mail_to, $subject, $title, $body);
        if($send){
            $request->session()->flash('success', 'Mail send successfully');
        }else{
            $request->session()->flash('error', trans('Can not send email. Please check your Email config.'));
        }
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function websiteConfig(Request $request){
        $post    = $request->post();
        $setting = Website::getWebsiteConfig();
        if($post){
            unset($post['_token']);
            foreach($post as $key => $value){
                $setting = Website::where('key', $key)->first();
                if(!empty($setting)){
                    $setting->update(['value' => $value]);
                }else{
                    $setting        = new MailConfig();
                    $setting->key   = $key;
                    $setting->value = $value;
                    $setting->save();
                }
            }

            $request->session()->flash('success', 'Website Config updated successfully.');

            return redirect()->back();
        }

        return view("Setting::setting.website", compact('setting'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function appointmentConfig(Request $request){
        $post    = $request->post();
        $setting = new AppointmentSetting();
        if($post){
            unset($post['_token']);
            foreach($post as $key => $value){
                $setting = AppointmentSetting::where('key', $key)->first();
                if(!empty($setting)){
                    $setting->update(['value' => $value]);
                }else{
                    $setting        = new AppointmentSetting();
                    $setting->key   = $key;
                    $setting->value = $value;
                    $setting->save();
                }
            }

            $request->session()->flash('success', 'Appointment Config updated successfully.');

            return redirect()->back();
        }

        return view("Setting::setting.appointment", compact('setting'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function commissionRateConfig(Request $request){
        $post  = $request->post();
        $roles = Role::getArray();
        unset($roles[Role::getAdminRole()->id]);
        if($post){
            $role_keys       = array_keys($roles);
            $company_incomes = array_map('intval', $post[CommissionRateSetting::COMPANY_INCOME] ?? []);
            $person_incomes  =
                array_merge(array_diff($company_incomes, $role_keys), array_diff($role_keys, $company_incomes));

            /** @var $company_income_data */
            $company_income_data = CommissionRateSetting::where('key', CommissionRateSetting::COMPANY_INCOME)->first();
            if(empty($company_income_data)){
                $company_income_data      = new CommissionRateSetting();
                $company_income_data->key = CommissionRateSetting::COMPANY_INCOME;
            }
            $company_income_data->value = json_encode($company_incomes);
            $company_income_data->save();

            /** @var $company_income_data */
            $person_income_data = CommissionRateSetting::where('key', CommissionRateSetting::PERSON_INCOME)->first();
            if(empty($person_income_data)){
                $person_income_data      = new CommissionRateSetting();
                $person_income_data->key = CommissionRateSetting::PERSON_INCOME;
            }
            $person_income_data->value = json_encode($person_incomes);
            $person_income_data->save();

            $request->session()->flash('success', 'Appointment Config updated successfully.');

            return redirect()->back();
        }

        $company_income_setting =
            json_decode(CommissionRateSetting::getValueByKey(CommissionRateSetting::COMPANY_INCOME), 1);
        $person_income_setting  =
            json_decode(CommissionRateSetting::getValueByKey(CommissionRateSetting::PERSON_INCOME), 1);

        $person_income_roles = [];
        if(!empty($person_income_setting)){
            $person_income_roles = Role::query()->whereIn('id', $person_income_setting)->get();
        }

        return view("Setting::setting.commission_rate", compact('roles', 'company_income_setting', 'person_income_roles'));
    }
}
