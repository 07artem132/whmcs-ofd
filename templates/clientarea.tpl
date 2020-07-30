{if $check != null}
    <div class='row'>
        <div class='col-md-6 col-md-offset-4'>
            <fieldset>
                <div class="form-group" style="height: 10px;">
                    <label class="col-sm-3 control-label" style="width: 150px;float: left;">Дата чека</label>
                    <div class="col-sm-5" style="float: left;">
                        <span>{$check.created_at}</span>
                    </div>
                </div>
                <div class="form-group" style="height: 10px;">
                    <label class="col-sm-3 control-label" style="width: 150px;float: left;">Тип операции</label>
                    <div class="col-sm-5" style="float: left;">
                        <span>{if $check.type eq 'Income'}Приход{else}Возврат{/if}</span>
                    </div>
                </div>
                <div class="form-group" style="height: 10px;">
                    <label class="col-sm-3 control-label" style="width: 150px;float: left;">Сумма</label>
                    <div class="col-sm-5" style="float: left;">
                        <span>{$check.total} RUB</span>
                    </div>
                </div>
                <div class="form-group" style="height: 10px;">
                    <label class="col-sm-3 control-label" style="width: 150px;float: left;">ФН №</label>
                    <div class="col-sm-5" style="float: left;">
                        <span>{$check.fn}</span>
                    </div>
                </div>
                <div class="form-group" style="height: 10px;">
                    <label class="col-sm-3 control-label" style="width: 150px;float: left;">ФД №</label>
                    <div class="col-sm-5" style="float: left;">
                        <span>{$check.fdn}</span>
                    </div>
                </div>
                <div class="form-group" style="height: 10px;">
                    <label class="col-sm-3 control-label" style="width: 150px;float: left;">ФПД</label>
                    <div class="col-sm-5" style="float: left;">
                        <span>{$check.fpd}</span>
                    </div>
                </div>
                <div class="form-group" style="height: 10px;">
                    <label class="col-sm-3 control-label" style="width: 150px;float: left;">Полный чек</label>
                    <div class="col-sm-5" style="float: left;">
                        <span><a href="https://ofd.ru/rec/{$check.fn}/{$check.fdn}/{$check.fpd}"
                                 target="_blank">Просмотреть</a></span>
                    </div>
                </div>
                <div class="form-group" style="height: 250px;">
                    <img src="data:image/png;base64,{$qr}" alt="qr code">
                </div>
            </fieldset>
        </div>
    </div>
{/if}