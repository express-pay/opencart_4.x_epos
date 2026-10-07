<?php
namespace Opencart\Admin\Controller\Extension\ExpresspayEpos\Payment;

class EposExpresspay extends \Opencart\System\Engine\Controller {
	const VERSION_EXTENSION = '1.0.2';

	public function index(): void {
		$this->load->language('extension/expresspay_epos/payment/epos_expresspay');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/expresspay_epos/payment/epos_expresspay', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/expresspay_epos/payment/epos_expresspay.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		$data['version_extension'] = self::VERSION_EXTENSION;

		// Load settings from config
		$data['payment_epos_expresspay_name_payment_method'] = $this->config->get('payment_epos_expresspay_name_payment_method');
		$data['payment_epos_expresspay_is_test_mode'] = $this->config->get('payment_epos_expresspay_is_test_mode');
		$data['payment_epos_expresspay_token'] = $this->config->get('payment_epos_expresspay_token');
		$data['payment_epos_expresspay_service_id'] = $this->config->get('payment_epos_expresspay_service_id');
		$data['payment_epos_expresspay_secret_word'] = $this->config->get('payment_epos_expresspay_secret_word');
		$data['payment_epos_expresspay_is_use_signature_for_notification'] = $this->config->get('payment_epos_expresspay_is_use_signature_for_notification');
		$data['payment_epos_expresspay_secret_word_for_notification'] = $this->config->get('payment_epos_expresspay_secret_word_for_notification');
		$data['payment_epos_expresspay_url_notification'] = $this->config->get('payment_epos_expresspay_url_notification');
		$data['payment_epos_expresspay_is_show_qr_code'] = $this->config->get('payment_epos_expresspay_is_show_qr_code');
		$data['payment_epos_expresspay_is_name_editable'] = $this->config->get('payment_epos_expresspay_is_name_editable');
		$data['payment_epos_expresspay_is_amount_editable'] = $this->config->get('payment_epos_expresspay_is_amount_editable');
		$data['payment_epos_expresspay_is_address_editable'] = $this->config->get('payment_epos_expresspay_is_address_editable');
		$data['payment_epos_expresspay_service_provider_id'] = $this->config->get('payment_epos_expresspay_service_provider_id');
		$data['payment_epos_expresspay_epos_service_id'] = $this->config->get('payment_epos_expresspay_epos_service_id');
		$data['payment_epos_expresspay_api_url'] = $this->config->get('payment_epos_expresspay_api_url');
		$data['payment_epos_expresspay_sandbox_url'] = $this->config->get('payment_epos_expresspay_sandbox_url');
		$data['payment_epos_expresspay_info'] = $this->config->get('payment_epos_expresspay_info');
		$data['payment_epos_expresspay_message_success'] = $this->config->get('payment_epos_expresspay_message_success');
		$data['payment_epos_expresspay_status'] = $this->config->get('payment_epos_expresspay_status');
		$data['payment_epos_expresspay_sort_order'] = $this->config->get('payment_epos_expresspay_sort_order');
		$data['payment_epos_expresspay_processed_status_id'] = $this->config->get('payment_epos_expresspay_processed_status_id');
		$data['payment_epos_expresspay_success_status_id'] = $this->config->get('payment_epos_expresspay_success_status_id');
		$data['payment_epos_expresspay_fail_status_id'] = $this->config->get('payment_epos_expresspay_fail_status_id');

		// Generate notification URL
		$data['payment_epos_expresspay_url_notification'] = $this->config->get('config_url') . 'index.php?route=extension/expresspay_epos/payment/epos_expresspay_api.notify';

		// Load order statuses
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/expresspay_epos/payment/epos_expresspay', $data));
	}

	public function save(): void {
		$this->load->language('extension/expresspay_epos/payment/epos_expresspay');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/expresspay_epos/payment/epos_expresspay')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['payment_epos_expresspay_name_payment_method'])) {
			$json['error']['name_payment_method'] = $this->language->get('errorNamePaymentMethod');
		}

		if (empty($this->request->post['payment_epos_expresspay_token'])) {
			$json['error']['token'] = $this->language->get('errorToken');
		}

		if (empty($this->request->post['payment_epos_expresspay_service_id'])) {
			$json['error']['service_id'] = $this->language->get('errorServiceId');
		}

		if (empty($this->request->post['payment_epos_expresspay_service_provider_id'])) {
			$json['error']['service_provider_id'] = $this->language->get('errorServiceProviderId');
		}

		if (empty($this->request->post['payment_epos_expresspay_epos_service_id'])) {
			$json['error']['epos_service_id'] = $this->language->get('errorEposServiceId');
		}

		if (empty($this->request->post['payment_epos_expresspay_api_url'])) {
			$json['error']['api_url'] = $this->language->get('errorAPIUrl');
		}

		if (empty($this->request->post['payment_epos_expresspay_sandbox_url'])) {
			$json['error']['sandbox_url'] = $this->language->get('errorSandboxUrl');
		}

		if (empty($this->request->post['payment_epos_expresspay_info'])) {
			$json['error']['info'] = $this->language->get('errorInfo');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('payment_epos_expresspay', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
