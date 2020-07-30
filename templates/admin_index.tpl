<div class="col-md-12">
    <div id="tableBackground" class="tablebg">
        <table id="tableInvoicesList" width="100%" class="datatable no-margin">
            <thead>
            <tr>
                <th>Номер</th>
                <th>Тип</th>
                <th>Статус</th>
                <th>транзакция</th>
                <th>Счет</th>
                <th>Сумма</th>
                <th>Создан</th>
                <th>Изменен</th>
                <th>Информация ККМ</th>
                <th>Просмотр чека</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$checks item=check}
                <tr>
                    <td>{$check.id}</td>
                    <td>{if $check.type eq 'Income'}Приход{else}Возврат{/if}</td>
                    <td>{$check.status_message}</td>
                    <td>{$check.order_id}</td>
                    <td><a href="invoices.php?action=edit&id={$check.transactions.invoiceid}">{$check.transactions.invoiceid}</a></td>
                    <td>{$check.total}</td>
                    <td>{$check.created_at}</td>
                    <td>{$check.updated_at}</td>
                    <td>
                        <table style="width: 100%;">
                            <tr>
                                <td>RNM</td>
                                <td>{$check.rnm}</td>
                            </tr>
                            <tr>
                                <td>FN</td>
                                <td>{$check.fn}</td>
                            </tr>
                        </table>
                    </td>
                    <td><a href="/?m=ofd&id={base64_encode(encrypt($check.id))}" target="_blank">Посмотреть</a></td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>