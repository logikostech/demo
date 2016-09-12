<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('verify that phalcon says congrats');
$I->amOnPage('/');
$I->see('Congratulations!');