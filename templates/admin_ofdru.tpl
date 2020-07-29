<form class="form-horizontal" method="post">
    <div class='row'>
        <div class='col-md-8 col-md-offset-2'>
            <fieldset>
                <div class="form-group">
                    <label for="ofr_ru_login" class="col-sm-3 control-label">Логин</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name='ofr_ru_login' id="ofr_ru_login"
                               value='{$settings.ofr_ru_login}' required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ofr_ru_password" class="col-sm-3 control-label">Пароль</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name='ofr_ru_password' id="ofr_ru_password"
                               value='{$settings.ofr_ru_password}' required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ofr_ru_inn" class="col-sm-3 control-label">ИНН организации</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name='ofr_ru_inn' id="ofr_ru_inn"
                               value='{$settings.ofr_ru_inn}' required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ofr_ru_admin_email" class="col-sm-3 control-label">Адрес e-mail для уведомлений о
                        сбое</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name='ofr_ru_admin_email' id="ofr_ru_admin_email"
                               value='{$settings.ofr_ru_admin_email}' required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ofr_ru_TaxationSystem" class="col-sm-3 control-label">Система налогообложения</label>
                    <div class="col-sm-9">
                        <select name='ofr_ru_TaxationSystem' id="ofr_ru_TaxationSystem" class="form-control" required>
                            <option  {if !('ofr_ru_TaxationSystem'|array_key_exists:$settings)}
                            selected
                                    {/if} disabled>Не выбрано
                            </option>
                            <option value='0' {if $settings.ofr_ru_TaxationSystem === 0}
                            selected
                                    {/if}>Общая система налогообложения
                            </option>
                            <option value='1' {if $settings.ofr_ru_TaxationSystem eq 1}
                            selected
                                    {/if}>Упрощенная система налогообложения (доход)
                            </option>
                            <option value='2' {if $settings.ofr_ru_TaxationSystem eq 2}
                            selected
                                    {/if}>Упрощенная система налогообложения (доход минус расход)
                            </option>
                            <option value='3' {if $settings.ofr_ru_TaxationSystem eq 3}
                            selected
                                    {/if}>Единый налог на вмененный доход
                            </option>
                            <option value='4' {if $settings.ofr_ru_TaxationSystem eq 4}
                            selected
                                    {/if}>Единый сельскохозяйственный налог
                            </option>
                            <option value='5' {if $settings.ofr_ru_TaxationSystem eq 5}
                            selected
                                    {/if}>Патентная система налогообложения
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ofr_ru_collapse" class="col-sm-3 control-label">Принудительная свертка позиций
                        заказа</label>
                    <div class="col-sm-9">
                        <select name='ofr_ru_collapse' id="ofr_ru_collapse" class="form-control" required>
                            <option {if !('ofr_ru_collapse'|array_key_exists:$settings)}
                                selected
                            {/if} disabled>Не выбрано
                            </option>
                            <option value='0' {if $settings.ofr_ru_collapse === 0}
                            selected
                                    {/if}>Нет
                            </option>
                            <option value='1' {if $settings.ofr_ru_collapse eq 1}
                            selected
                                    {/if}>Да
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ofr_ru_collapse_name" class="col-sm-3 control-label">Текстовое название для такой
                        позиции</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name='ofr_ru_collapse_name' id="ofr_ru_collapse_name"
                               value="{$settings.ofr_ru_collapse_name}" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="ofr_ru_vat" class="col-sm-3 control-label">Ставка НДС по умолчанию</label>
                    <div class="col-sm-9">
                        <select name='ofr_ru_vat' id="ofr_ru_vat" class="form-control" required>
                            <option {if !('ofr_ru_vat'|array_key_exists:$settings)}
                                selected
                            {/if} disabled>Не выбрано
                            </option>
                            <option value='Vat10' {if $settings.ofr_ru_vat eq 'Vat10'}
                            selected
                                    {/if}>НДС 10%
                            </option>
                            <option value='Vat18' {if $settings.ofr_ru_vat eq 'Vat18'}
                            selected
                                    {/if}>НДС 18%
                            </option>
                            <option value='Vat20' {if $settings.ofr_ru_vat eq 'Vat20'}
                            selected
                                    {/if}>НДС 20%
                            </option>
                            <option value='Vat0' {if $settings.ofr_ru_vat eq 'Vat0'}
                            selected
                                    {/if}>НДС 0%
                            </option>
                            <option value='VatNo' {if $settings.ofr_ru_vat eq 'VatNo'}
                            selected
                                    {/if}>НДС не облагается
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ofr_ru_gateway_enabled" class="col-sm-3 control-label">Выбор платежных шлюзов для каких
                        будет
                        отправка чеков.</label>
                    <div class="col-sm-9">
                        <select name='ofr_ru_gateway_enabled[]' id="ofr_ru_gateway_enabled" multiple class="form-control">
                            {html_options  options=$AvailableGateways selected=$settings.ofr_ru_gateway_enabled}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ofr_ru_payment_method" class="col-sm-3 control-label">Способ расчёта
                        по-умолчанию</label>
                    <div class="col-sm-9">
                        <select name='ofr_ru_payment_method' id="ofr_ru_payment_method" class="form-control">
                            <option {if !('ofr_ru_payment_method'|array_key_exists:$settings)}
                                selected
                            {/if} disabled>Не выбрано
                            </option>
                            <option value='1' {if $settings.ofr_ru_payment_method == 1}
                            selected
                                    {/if}>предоплата 100%
                            </option>
                            <option value='2' {if $settings.ofr_ru_payment_method eq 2}
                            selected
                                    {/if}>предоплата
                            </option>
                            <option value='3' {if $settings.ofr_ru_payment_method eq 3}
                            selected
                                    {/if}>аванс
                            </option>
                            <option value='4' {if $settings.ofr_ru_payment_method eq 4}
                            selected
                                    {/if}>полный расчет
                            </option>
                            <option value='5' {if $settings.ofr_ru_payment_method eq 5}
                            selected
                                    {/if}>частичный расчет
                            </option>
                            <option value='6' {if $settings.ofr_ru_payment_method eq 6}
                            selected
                                    {/if}>передача в кредит
                            </option>
                            <option value='7' {if $settings.ofr_ru_payment_method eq 7}
                            selected
                                    {/if}>оплата в кредит
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ofr_ru_payment_type" class="col-sm-3 control-label">Предмет расчёта по-умолчанию</label>
                    <div class="col-sm-9">
                        <select name='ofr_ru_payment_type' id="ofr_ru_payment_type"  class="form-control">
                            <option {if !('ofr_ru_payment_type'|array_key_exists:$settings)}
                                selected
                            {/if} disabled>Не выбрано
                            </option>
                            <option  value='1' {if $settings.ofr_ru_payment_type eq 1}
                            selected
                                    {/if}>ТОВАР
                            </option>
                            <option value='2' {if $settings.ofr_ru_payment_type eq 2}
                            selected
                                    {/if}>ПОДАКЦИЗНЫЙ ТОВАР
                            </option>
                            <option value='3' {if $settings.ofr_ru_payment_type eq 3}
                            selected
                                    {/if}>РАБОТА
                            </option>
                            <option value='4' {if $settings.ofr_ru_payment_type eq 4}
                            selected
                                    {/if}>УСЛУГА
                            </option>
                            <option value='5' {if $settings.ofr_ru_payment_type eq 5}
                            selected
                                    {/if}>СТАВКА АЗАРТНОЙ ИГРЫ
                            </option>
                            <option value='6' {if $settings.ofr_ru_payment_type eq 6}
                            selected
                                    {/if}>ВЫИГРЫШ АЗАРТНОЙ ИГРЫ
                            </option>
                            <option value='7' {if $settings.ofr_ru_payment_type eq 7}
                            selected
                                    {/if}>ЛОТЕРЕЙНЫЙ БИЛЕТ
                            </option>
                            <option value='8' {if $settings.ofr_ru_payment_type eq 8}
                            selected
                                    {/if}>ВЫИГРЫШ ЛОТЕРЕИ
                            </option>
                            <option value='9' {if $settings.ofr_ru_payment_type eq 9}
                            selected
                                    {/if}>ПРЕДОСТАВЛЕНИЕ РИД
                            </option>
                            <option value='10' {if $settings.ofr_ru_payment_type eq 10}
                            selected
                                    {/if}>ПЛАТЕЖ
                            </option>
                            <option value='11' {if $settings.ofr_ru_payment_type eq 11}
                            selected
                                    {/if}>АГЕНТСКОЕ ВОЗНАГРАЖДЕНИЕ
                            </option>
                            <option value='12' {if $settings.ofr_ru_payment_type eq 12}
                            selected
                                    {/if}>СОСТАВНОЙ ПРЕДМЕТ РАСЧЕТА
                            </option>
                            <option value='13' {if $settings.ofr_ru_payment_type eq 13}
                            selected
                                    {/if}>ИНОЙ ПРЕДМЕТ РАСЧЕТА</option>
                            <option value='14' {if $settings.ofr_ru_payment_type eq 14}
                            selected
                                    {/if}>ИМУЩЕСТВЕННОЕ ПРАВО
                            </option>
                            <option value='15' {if $settings.ofr_ru_payment_type eq 15}
                            selected
                                    {/if}>ВНЕРЕАЛИЗАЦИОННЫЙ ДОХОД
                            </option>
                            <option value='16' {if $settings.ofr_ru_payment_type eq 16}
                            selected
                                    {/if}>СТРАХОВЫЕ ВЗНОСЫ
                            </option>
                            <option value='17' {if $settings.ofr_ru_payment_type eq 17}
                            selected
                                    {/if}>ТОРГОВЫЙ СБОР
                            </option>
                            <option value='18' {if $settings.ofr_ru_payment_type eq 18}
                            selected
                                    {/if}>КУРОРТНЫЙ СБОР
                            </option>
                            <option value='19' {if $settings.ofr_ru_payment_type eq 19}
                            selected
                                    {/if}>ЗАЛОГ
                            </option>
                        </select>
                    </div>
                </div>
            </fieldset>
            <hr>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-primary center-block">
                        <i class='fa fa-floppy-o'></i>
                        &nbsp;&nbsp;Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function () {
        $('#ofr_ru_payment_type').selectpicker();
    });
</script>
