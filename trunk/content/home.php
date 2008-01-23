<!-- MENU -->
<div class="topNavigation">
    <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Accounts</a></li>
        <li><a href="#">Buckets</a></li>
        <li><a href="#">Reports</a></li>
        <li><a href="#">Logout</a></li>
    </ul>
</div>

<?php $sys = new SystemRoot(); ?>

<div class="column">

    <!-- ACCOUNTS -->
    <div>
        <fieldset>
            <legend>Accounts (edit)</legend>
            <table>
            <?php foreach($sys->getAccountPodList() as $acctID => $acct) { ?>
            
                <!-- ACCT -->
                <tr>
                    <td><input type="checkbox" /></td>
                    <td><?php echo $acct['name']; ?></td>
                    <td><?php echo kSystem_currency($acct['balance']); ?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>Pending</td>
                    <td><?php echo kSystem_currency($acct['pending']); ?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>Shown</td>
                    <td><?php echo kSystem_currency($acct['shown']); ?></td>
                </tr>
                
            <?php } ?>  
            </table>
        </fieldset>
    </div>
    
    <!-- BUCKETS -->
    <div>
        <fieldset>
            <legend>Buckets (edit)</legend>
            <table>
                <tr>
                    <td><input type="checkbox" /></td>
                    <td>Salary</td>
                    <td>$63.05</td>
                </tr>
                <tr>
                    <td><input type="checkbox" /></td>
                    <td>Rent</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td><input type="checkbox" /></td>
                    <td>Car Payment</td>
                    <td>$200.00</td>
                </tr>
                <tr>
                    <td><input type="checkbox" /></td>
                    <td>Car Insurance</td>
                    <td>$105.00</td>
                </tr>
                <tr>
                    <td><input type="checkbox" /></td>
                    <td>Car - Gas</td>
                    <td>$250.00</td>
                </tr>
                
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <td colspan="2">Total Allocations</td>
                    <td>$3,548.96</td>
                </tr>
            </table>
        </fieldset>
    </div>

</div>
    
<div class="column">
    
    <!-- NEW TRANSACTIONS -->
    <div>
        <fieldset>
            <legend>New Transaction</legend>
            
        </fieldset>
    </div>

    <!-- TRANSACTIONS -->
    <div class="transactions">
        <fieldset>
            <legend>Transactions :: 11/15/2007 - Today</legend>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Payee</th>
                    <th>Memo</th>
                    <th>Amount</th>
                    <th>Cleared</th>
                </tr>
                
                <?php $p = array("accountID = 1"); ?>
                <?php foreach($sys->getTransactionList($p) as $transID => $trans) { ?>
				
                <tr>
                    <td><?php echo kSystem_date($trans['date']); ?></td>
                    <td><?php echo $trans['type']; ?></td>
                    <td><?php echo $trans['payee']; ?></td>
                    <td><?php echo $trans['memo']; ?></td>
                    <td><?php echo kSystem_currency($trans['amount']); ?></td>
                    <td><input type="checkbox" 
                    	<?php echo $trans['cleared'] ? "CHECKED" : "" ?> /></td>
                </tr>
                
                <?php } ?>

            </table>
            
        </fieldset>
    </div>
    
</div>    