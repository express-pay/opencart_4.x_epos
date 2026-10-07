<?php
namespace Opencart\Catalog\Controller\Extension\ExpresspayEpos\Payment;

class EposExpresspay extends \Opencart\System\Engine\Controller {
	const IS_SHOW_QR_CODE_PARAM_NAME = 'payment_epos_expresspay_is_show_qr_code';
	const SERVICE_PROVIDER_ID_PARAM_NAME = 'payment_epos_expresspay_service_provider_id';
	const EPOS_SERVICE_ID_PARAM_NAME = 'payment_epos_expresspay_epos_service_id';
	const MESSAGE_SUCCESS_PARAM_NAME = 'payment_epos_expresspay_message_success';
	const PROCESSED_STATUS_ID_PARAM_NAME = 'payment_epos_expresspay_processed_status_id';
	const FAIL_STATUS_ID_PARAM_NAME = 'payment_epos_expresspay_fail_status_id';

	public function index() {
		$this->load->model('extension/expresspay_epos/payment/epos_expresspay');
		$this->load->model('extension/expresspay_epos/payment/epos_expresspay_log');

		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_loading'] = $this->language->get('text_loading');

		$data = $this->model_extension_expresspay_epos_payment_epos_expresspay->setParams($data, $this->config);

		$this->model_extension_expresspay_epos_payment_epos_expresspay_log->log_info("index", "DATA: " . json_encode($data));

		return $this->load->view('extension/expresspay_epos/payment/epos_expresspay', $data);
	}

	public function success() {
		if (empty($this->session->data['order_id'])) {
			$this->response->redirect($this->url->link('checkout/checkout'));
			return;
		}

		$this->cart->clear();
		$this->load->model('extension/expresspay_epos/payment/epos_expresspay');
		$this->load->model('extension/expresspay_epos/payment/epos_expresspay_log');
		$this->load->language('extension/expresspay_epos/payment/epos_expresspay');
		$this->model_extension_expresspay_epos_payment_epos_expresspay_log->log_info("successStart", "Order Id: " . $this->session->data['order_id']);
		$headingTitle = $this->language->get('heading_title_success');
		$this->document->setTitle($headingTitle);
		$data['heading_title'] = $headingTitle;

		$textMessage = $this->config->get(self::MESSAGE_SUCCESS_PARAM_NAME);
		if (empty($textMessage)) {
			$textMessage = $this->language->get('text_message_success');
		}
		$data['text_message'] = nl2br(str_replace('##order_id##', $this->session->data['order_id'], $textMessage));

		$eripPath = $this->language->get('erip_path');
		$eposCode = $this->config->get(self::SERVICE_PROVIDER_ID_PARAM_NAME) . '-' . $this->config->get(self::EPOS_SERVICE_ID_PARAM_NAME) . '-' . $this->session->data['order_id'];
		$data['content_body'] = str_replace('##erip_path##', $eripPath, $this->language->get('content_success'));
		$data['content_body'] = nl2br(str_replace('##order_id##', $eposCode, $data['content_body']));

		$data['button_continue'] = $this->language->get('button_continue');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['qr_description'] = $this->language->get('qr_description');

		if ($this->config->get(self::IS_SHOW_QR_CODE_PARAM_NAME) == '1' && isset($this->request->get['ExpressPayInvoiceNo'])) {
			$invoiceNo = $this->request->get['ExpressPayInvoiceNo'];
			try {
				$qrbase64json = $this->model_extension_expresspay_epos_payment_epos_expresspay->getQrbase64($invoiceNo, $this->config);
				$qrbase64 = json_decode($qrbase64json);
				if (isset($qrbase64->QrCodeBody)) {
					$data['qr_code'] = $qrbase64->QrCodeBody;
					$data['show_qr_code'] = 1;
				}
			} catch (Exception $e) {
				$this->model_extension_expresspay_epos_payment_epos_expresspay_log->log_error_exception('success', 'Get response; INVOICE ID - ' . $invoiceNo . '; RESPONSE - ' . $qrbase64json, $e);
			}
		}

		$this->load->model('checkout/order');
		$this->model_checkout_order->addHistory($this->session->data['order_id'], $this->config->get(self::PROCESSED_STATUS_ID_PARAM_NAME));

		unset($this->session->data['order_id']);

		$data['continue'] = $this->url->link('common/home');

		$this->model_extension_expresspay_epos_payment_epos_expresspay_log->log_info("successFinish", "DATA: " . json_encode($data));
		$this->response->setOutput($this->load->view('extension/expresspay_epos/payment/epos_expresspay_successful', $data));
	}

	public function fail() {
		if (empty($this->session->data['order_id'])) {
			$this->response->redirect($this->url->link('checkout/checkout'));
			return;
		}

		$this->load->model('extension/expresspay_epos/payment/epos_expresspay_log');
		$this->load->language('extension/expresspay_epos/payment/epos_expresspay');
		$this->model_extension_expresspay_epos_payment_epos_expresspay_log->log_info("failStart", "Order Id: " . $this->session->data['order_id']);
		$headingTitle = $this->language->get('heading_title_fail');
		$this->document->setTitle($headingTitle);
		$data['heading_title'] = $headingTitle;

		$data['text_message'] = nl2br(str_replace('##order_id##', $this->session->data['order_id'], $this->language->get('text_message_fail')));

		$this->load->model('checkout/order');
		$this->model_checkout_order->addHistory($this->session->data['order_id'], $this->config->get(self::FAIL_STATUS_ID_PARAM_NAME));

		unset($this->session->data['order_id']);

		$data['continue'] = $this->url->link('checkout/checkout');

		$this->model_extension_expresspay_epos_payment_epos_expresspay_log->log_info("failFinish", "DATA: " . json_encode($data));
		$this->response->setOutput($this->load->view('extension/expresspay_epos/payment/epos_expresspay_failure', $data));
	}
}
