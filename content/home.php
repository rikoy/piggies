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
                    <td><?php echo $acct['balance']; ?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>Pending</td>
                    <td><?php echo $acct['pending']; ?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>Shown</td>
                    <td><?php echo $acct['shown']; ?></td>
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
            <form>
                <table>
                    <tr>
                        <td>
                            Date:&nbsp;
                            <input size="10" value="<?php echo date("n/j/Y"); ?>" type="text" />
                        </td>
                        <td>
                            Type of transaction:&nbsp;
                            <select name="nt_type">
                                <option value="withdrawal">Withdrawal</option>
                                <option value="deposit">Deposit</option>
                                <option value="transfer">Transfer</option>
                                <option value="bucket">Bucket</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            From:&nbsp;
                        </td>
                        <td>
                            <select name="from_account">
                                <option value="">...account...</option>
                            </select>
                            <select name="from_bucket">
                                <option value="">...bucket...</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            To:&nbsp;
                        </td>
                        <td>
                            <select name="to_account">
                                <option value="">...account...</option>
                            </select>
                            <select name="to_bucket">
                                <option value="">...bucket...</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Payee:&nbsp;
                            <input type="text" />
                        </td>
                        <td>
                            Amount:&nbsp;
                            <input type="text" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Memo:<br />
                            <textarea cols="60" rows="6"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="Submit" />
                        </td>
                    </tr>
                </table>
            </form>
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
                
                <tr>
                    <td>1/2/2008</td>
                    <td>Withdrawal</td>
                    <td>Linnea Petrillo</td>
                    <td>Repayment of Loan</td>
                    <td>$400.00</td>
                    <td><input type="checkbox" /></td>
                </tr>
                
                <tr>
                    <td>1/2/2008</td>
                    <td>Withdrawal</td>
                    <td>Linnea Petrillo</td>
                    <td>Repayment of Loan</td>
                    <td>$400.00</td>
                    <td><input type="checkbox" /></td>
                </tr>
                
                <tr>
                    <td>1/2/2008</td>
                    <td>Withdrawal</td>
                    <td>Linnea Petrillo</td>
                    <td>Repayment of Loan</td>
                    <td>$400.00</td>
                    <td><input type="checkbox" /></td>
                </tr>
                
                <tr>
                    <td>1/2/2008</td>
                    <td>Withdrawal</td>
                    <td>Linnea Petrillo</td>
                    <td>Repayment of Loan</td>
                    <td>$400.00</td>
                    <td><input type="checkbox" /></td>
                </tr>
                
                <tr>
                    <td>1/2/2008</td>
                    <td>Withdrawal</td>
                    <td>Linnea Petrillo</td>
                    <td>Repayment of Loan</td>
                    <td>$400.00</td>
                    <td><input type="checkbox" /></td>
                </tr>
                
                <tr>
                    <td>1/2/2008</td>
                    <td>Withdrawal</td>
                    <td>Linnea Petrillo</td>
                    <td>Repayment of Loan</td>
                    <td>$400.00</td>
                    <td><input type="checkbox" /></td>
                </tr>

            </table>
            
        </fieldset>
    </div>
    
</div>    