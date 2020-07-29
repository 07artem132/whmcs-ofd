<div class="col-md-12">
    <div id="tableBackground" class="tablebg">
        <table id="tableTransactionList" width="100%" class="datatable no-margin">
            <thead>
            <tr>
                <th>Номер транзакции</th>
                <th>Счет</th>
                <th>Тип</th>
                <th>Платежный шлюз</th>
                <th>Создана</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$transactions item=transaction}
                <tr>
                    <td>{$transaction.transid}</td>
                    <td><a href="invoices.php?action=edit&id={$transaction.invoiceid}">{$transaction.invoiceid}</a></td>
                    <td>
                        {if $transaction.refundid eq 0 }
                            Поступление
                        {else}
                            Возврат
                        {/if}
                    </td>
                    <td>{$transaction.gateway}</td>
                    <td>{$transaction.date}</td>

                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>