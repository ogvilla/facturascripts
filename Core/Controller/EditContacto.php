<?php
/**
 * This file is part of FacturaScripts
 * Copyright (C) 2018-2019 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Core\Controller;

use FacturaScripts\Core\Lib\ExtendedController\BaseView;
use FacturaScripts\Core\Lib\ExtendedController\EditController;

/**
 * Controller to edit a single item from the Contacto model
 *
 * @author Carlos García Gómez <carlos@facturascripts.com>
 */
class EditContacto extends EditController
{

    /**
     * 
     * @return string
     */
    public function getImageUrl()
    {
        return $this->views['EditContacto']->model->gravatar();
    }

    /**
     * 
     * @return string
     */
    public function getModelClassName()
    {
        return 'Contacto';
    }

    /**
     * Returns basic page attributes
     *
     * @return array
     */
    public function getPageData()
    {
        $data = parent::getPageData();
        $data['menu'] = 'sales';
        $data['title'] = 'contact';
        $data['icon'] = 'fas fa-address-book';
        return $data;
    }

    /**
     * 
     * @param string $viewName
     */
    protected function addConversionButtons($viewName)
    {
        if (empty($this->views[$viewName]->model->codcliente)) {
            $customerButton = [
                'action' => 'convert-into-customer',
                'color' => 'success',
                'icon' => 'fas fa-user-check',
                'label' => 'convert-into-customer',
                'type' => 'action',
            ];
            $this->addButton($viewName, $customerButton);
        }

        if (empty($this->views[$viewName]->model->codproveedor)) {
            $supplierButton = [
                'action' => 'convert-into-supplier',
                'color' => 'success',
                'icon' => 'fas fa-user-cog',
                'label' => 'convert-into-supplier',
                'type' => 'action',
            ];
            $this->addButton($viewName, $supplierButton);
        }
    }

    /**
     * Run the controller after actions
     *
     * @param string $action
     */
    protected function execAfterAction($action)
    {
        switch ($action) {
            case 'convert-into-customer':
                $customer = $this->views['EditContacto']->model->getCustomer();
                if ($customer->exists()) {
                    $this->miniLog->info($this->i18n->trans('record-updated-correctly'));
                    $this->redirect($customer->url() . '&action=save-ok');
                    break;
                }

                $this->miniLog->error($this->i18n->trans('record-save-error'));
                break;

            case 'convert-into-supplier':
                $supplier = $this->views['EditContacto']->model->getSupplier();
                if ($supplier->exists()) {
                    $this->miniLog->info($this->i18n->trans('record-updated-correctly'));
                    $this->redirect($supplier->url() . '&action=save-ok');
                    break;
                }

                $this->miniLog->error($this->i18n->trans('record-save-error'));
                break;
        }

        return parent::execAfterAction($action);
    }

    /**
     * 
     * @param string   $viewName
     * @param BaseView $view
     */
    protected function loadData($viewName, $view)
    {
        switch ($viewName) {
            case 'EditContacto':
                parent::loadData($viewName, $view);
                if ($view->model->exists()) {
                    $this->addConversionButtons($viewName);
                }
                break;

            default:
                parent::loadData($viewName, $view);
                break;
        }
    }
}
