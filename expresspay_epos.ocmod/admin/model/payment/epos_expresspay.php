<?php
namespace Opencart\Admin\Model\Extension\ExpresspayEpos\Payment;

class EposExpresspay extends \Opencart\System\Engine\Model {
	const NOTIFICATION_URL = 'index.php?route=extension/expresspay_epos/payment/epos_expresspay_api.notify';

	public function setParametersFromConfig($config, $request, $data) {
		$data['payment_epos_expresspay_name_payment_method'] = isset($request['payment_epos_expresspay_name_payment_method'])
			? $request['payment_epos_expresspay_name_payment_method']
			: ($config->get('payment_epos_expresspay_name_payment_method') ?: $this->language->get('namePaymentMethodDefault'));

		$data['payment_epos_expresspay_token'] = isset($request['payment_epos_expresspay_token'])
			? $request['payment_epos_expresspay_token']
			: $config->get('payment_epos_expresspay_token');

		$data['payment_epos_expresspay_service_id'] = isset($request['payment_epos_expresspay_service_id'])
			? $request['payment_epos_expresspay_service_id']
			: $config->get('payment_epos_expresspay_service_id');

		$data['payment_epos_expresspay_secret_word'] = isset($request['payment_epos_expresspay_secret_word'])
			? $request['payment_epos_expresspay_secret_word']
			: $config->get('payment_epos_expresspay_secret_word');

		$data['payment_epos_expresspay_is_use_signature_for_notification'] = isset($request['payment_epos_expresspay_is_use_signature_for_notification'])
			? $this->normCheckboxValue($request['payment_epos_expresspay_is_use_signature_for_notification'])
			: $this->normCheckboxValue($config->get('payment_epos_expresspay_is_use_signature_for_notification'));

		$data['payment_epos_expresspay_secret_word_for_notification'] = isset($request['payment_epos_expresspay_secret_word_for_notification'])
			? $request['payment_epos_expresspay_secret_word_for_notification']
			: $config->get('payment_epos_expresspay_secret_word_for_notification');

		$data['payment_epos_expresspay_is_show_qr_code'] = isset($request['payment_epos_expresspay_is_show_qr_code'])
			? $this->normCheckboxValue($request['payment_epos_expresspay_is_show_qr_code'])
			: $this->normCheckboxValue($config->get('payment_epos_expresspay_is_show_qr_code'));

		$data['payment_epos_expresspay_is_name_editable'] = isset($request['payment_epos_expresspay_is_name_editable'])
			? $this->normCheckboxValue($request['payment_epos_expresspay_is_name_editable'])
			: $this->normCheckboxValue($config->get('payment_epos_expresspay_is_name_editable'));

		$data['payment_epos_expresspay_is_amount_editable'] = isset($request['payment_epos_expresspay_is_amount_editable'])
			? $this->normCheckboxValue($request['payment_epos_expresspay_is_amount_editable'])
			: $this->normCheckboxValue($config->get('payment_epos_expresspay_is_amount_editable'));

		$data['payment_epos_expresspay_is_address_editable'] = isset($request['payment_epos_expresspay_is_address_editable'])
			? $this->normCheckboxValue($request['payment_epos_expresspay_is_address_editable'])
			: $this->normCheckboxValue($config->get('payment_epos_expresspay_is_address_editable'));

		$data['payment_epos_expresspay_service_provider_id'] = isset($request['payment_epos_expresspay_service_provider_id'])
			? $request['payment_epos_expresspay_service_provider_id']
			: $config->get('payment_epos_expresspay_service_provider_id');

		$data['payment_epos_expresspay_epos_service_id'] = isset($request['payment_epos_expresspay_epos_service_id'])
			? $request['payment_epos_expresspay_epos_service_id']
			: $config->get('payment_epos_expresspay_epos_service_id');

		$data['payment_epos_expresspay_is_test_mode'] = isset($request['payment_epos_expresspay_is_test_mode'])
			? $this->normCheckboxValue($request['payment_epos_expresspay_is_test_mode'])
			: $this->normCheckboxValue($config->get('payment_epos_expresspay_is_test_mode'));

		$data['payment_epos_expresspay_api_url'] = isset($request['payment_epos_expresspay_api_url'])
			? $request['payment_epos_expresspay_api_url']
			: ($config->get('payment_epos_expresspay_api_url') ?: 'https://api.express-pay.by/v1/');

		$data['payment_epos_expresspay_sandbox_url'] = isset($request['payment_epos_expresspay_sandbox_url'])
			? $request['payment_epos_expresspay_sandbox_url']
			: ($config->get('payment_epos_expresspay_sandbox_url') ?: 'https://sandbox-api.express-pay.by/v1/');

		$data['payment_epos_expresspay_info'] = isset($request['payment_epos_expresspay_info'])
			? $request['payment_epos_expresspay_info']
			: ($config->get('payment_epos_expresspay_info') ?: $this->language->get('infoDefault'));

		$data['payment_epos_expresspay_message_success'] = isset($request['payment_epos_expresspay_message_success'])
			? $request['payment_epos_expresspay_message_success']
			: ($config->get('payment_epos_expresspay_message_success') ?: $this->language->get('messageSuccessDefault'));

		$data['payment_epos_expresspay_status'] = isset($request['payment_epos_expresspay_status'])
			? $this->normCheckboxValue($request['payment_epos_expresspay_status'])
			: $this->normCheckboxValue($config->get('payment_epos_expresspay_status'));

		$data['payment_epos_expresspay_sort_order'] = isset($request['payment_epos_expresspay_sort_order'])
			? $request['payment_epos_expresspay_sort_order']
			: $config->get('payment_epos_expresspay_sort_order');

		$data['payment_epos_expresspay_processed_status_id'] = isset($request['payment_epos_expresspay_processed_status_id'])
			? $request['payment_epos_expresspay_processed_status_id']
			: $config->get('payment_epos_expresspay_processed_status_id');

		$data['payment_epos_expresspay_success_status_id'] = isset($request['payment_epos_expresspay_success_status_id'])
			? $request['payment_epos_expresspay_success_status_id']
			: $config->get('payment_epos_expresspay_success_status_id');

		$data['payment_epos_expresspay_fail_status_id'] = isset($request['payment_epos_expresspay_fail_status_id'])
			? $request['payment_epos_expresspay_fail_status_id']
			: $config->get('payment_epos_expresspay_fail_status_id');

		$data['payment_epos_expresspay_url_notification'] = $this->config->get('config_url') . self::NOTIFICATION_URL;

		return $data;
	}

	private function normCheckboxValue($checkboxValue) {
		$normValue = 0;

		if ($checkboxValue == null) {
			return $normValue;
		}

		switch ($checkboxValue) {
			case "on":
			case 1:
			case "1":
				$normValue = 1;
		}

		return $normValue;
	}
}
