<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Controller;

/**
 * Currency Controller.
 */
class CurrencyController extends BaseController
{
    /**
     * Display all currency rates and form.
     *
     * @param array $values
     * @param array $errors
     */
    public function index(array $values = [], array $errors = [])
    {
        $this->response->html($this->helper->layout->setting('admin/setting/currency', [
            'config_values' => ['application_currency' => $this->settingModel->get('application_currency')],
            'values'        => $values,
            'errors'        => $errors,
            'rates'         => $this->currencyModel->getAll(),
            'currencies'    => $this->currencyModel->getCurrencies(),
            'title'         => t('Settings').' &raquo; '.t('Currency rates'),
        ]));
    }

    /**
     * Validate and save a new currency rate.
     */
    public function create()
    {
        $values = $this->request->getValues();
        list($valid, $errors) = $this->currencyValidator->validateCreation($values);

        if ($valid) {
            if ($this->currencyModel->create($values['currency'], $values['rate'])) {
                $this->flash->success(t('The currency rate have been added successfully.'));

                return $this->response->redirect($this->helper->url->to('CurrencyController', 'index'));
            } else {
                $this->flash->failure(t('Unable to add this currency rate.'));
            }
        }

        return $this->index($values, $errors);
    }

    /**
     * Save reference currency.
     */
    public function reference()
    {
        $values = $this->request->getValues();

        if ($this->settingModel->save($values)) {
            $this->flash->success(t('Settings saved successfully.'));
        } else {
            $this->flash->failure(t('Unable to save your settings.'));
        }

        $this->response->redirect($this->helper->url->to('CurrencyController', 'index'));
    }
}
