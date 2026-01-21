<?php

use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\User;
use Livewire\Volt\Volt;

test('guests cannot access account transactions index', function () {
    $response = $this->get(route('account-transactions.index'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can view account transactions index', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('account-transactions.index'));
    $response->assertOk();
});

test('account transactions index displays transactions', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create();
    $transaction = AccountTransaction::factory()->create([
        'account_id' => $account->id,
        'description' => 'Test Transaction',
    ]);

    $this->actingAs($user);

    $response = $this->get(route('account-transactions.index'));
    $response->assertSee('Test Transaction');
});

test('can create a debit transaction', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create();

    $this->actingAs($user);

    Volt::test('pages.account-transactions.create')
        ->set('account_id', $account->id)
        ->set('type', 'debit')
        ->set('amount_cents', '100.50')
        ->set('description', 'Test Debit')
        ->set('transaction_date', now()->format('Y-m-d\TH:i'))
        ->call('create')
        ->assertHasNoErrors();

    expect(AccountTransaction::where('description', 'Test Debit')->exists())->toBeTrue();

    $transaction = AccountTransaction::where('description', 'Test Debit')->first();
    expect($transaction->amount_cents)->toBe(10050);
    expect($transaction->type)->toBe('debit');
});

test('can create a credit transaction', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create();

    $this->actingAs($user);

    Volt::test('pages.account-transactions.create')
        ->set('account_id', $account->id)
        ->set('type', 'credit')
        ->set('amount_cents', '250.00')
        ->set('description', 'Test Credit')
        ->set('transaction_date', now()->format('Y-m-d\TH:i'))
        ->call('create')
        ->assertHasNoErrors();

    expect(AccountTransaction::where('description', 'Test Credit')->exists())->toBeTrue();

    $transaction = AccountTransaction::where('description', 'Test Credit')->first();
    expect($transaction->amount_cents)->toBe(25000);
    expect($transaction->type)->toBe('credit');
});

test('can update a transaction', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create();
    $transaction = AccountTransaction::factory()->create([
        'account_id' => $account->id,
        'description' => 'Original Description',
    ]);

    $this->actingAs($user);

    Volt::test('pages.account-transactions.edit', ['transaction' => $transaction])
        ->set('description', 'Updated Description')
        ->set('amount_cents', '150.00')
        ->call('update')
        ->assertHasNoErrors();

    $transaction->refresh();
    expect($transaction->description)->toBe('Updated Description');
    expect($transaction->amount_cents)->toBe(15000);
});

test('can delete a transaction', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create();
    $transaction = AccountTransaction::factory()->create([
        'account_id' => $account->id,
    ]);

    $this->actingAs($user);

    Volt::test('pages.account-transactions.index')
        ->call('delete', $transaction->id);

    expect(AccountTransaction::find($transaction->id))->toBeNull();
});

test('transaction creation requires all required fields', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('pages.account-transactions.create')
        ->set('account_id', null)
        ->set('type', '')
        ->set('amount_cents', '')
        ->set('description', '')
        ->set('transaction_date', '')
        ->call('create')
        ->assertHasErrors(['account_id', 'type', 'amount_cents', 'description', 'transaction_date']);
});

test('can filter transactions by account', function () {
    $user = User::factory()->create();
    $account1 = Account::factory()->create(['name' => 'Account 1']);
    $account2 = Account::factory()->create(['name' => 'Account 2']);

    AccountTransaction::factory()->create([
        'account_id' => $account1->id,
        'description' => 'Transaction 1',
    ]);
    AccountTransaction::factory()->create([
        'account_id' => $account2->id,
        'description' => 'Transaction 2',
    ]);

    $this->actingAs($user);

    Volt::test('pages.account-transactions.index')
        ->set('account_id', $account1->id)
        ->assertSee('Transaction 1')
        ->assertDontSee('Transaction 2');
});

test('can filter transactions by type', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create();

    AccountTransaction::factory()->debit()->create([
        'account_id' => $account->id,
        'description' => 'Debit Transaction',
    ]);
    AccountTransaction::factory()->credit()->create([
        'account_id' => $account->id,
        'description' => 'Credit Transaction',
    ]);

    $this->actingAs($user);

    Volt::test('pages.account-transactions.index')
        ->set('type', 'debit')
        ->assertSee('Debit Transaction')
        ->assertDontSee('Credit Transaction');
});

test('can search transactions', function () {
    $user = User::factory()->create();
    $account = Account::factory()->create();

    AccountTransaction::factory()->create([
        'account_id' => $account->id,
        'description' => 'Monthly Payment',
    ]);
    AccountTransaction::factory()->create([
        'account_id' => $account->id,
        'description' => 'Annual Fee',
    ]);

    $this->actingAs($user);

    Volt::test('pages.account-transactions.index')
        ->set('search', 'Monthly')
        ->assertSee('Monthly Payment')
        ->assertDontSee('Annual Fee');
});

test('isDebit method returns correct value', function () {
    $debit = AccountTransaction::factory()->debit()->make();
    $credit = AccountTransaction::factory()->credit()->make();

    expect($debit->isDebit())->toBeTrue();
    expect($credit->isDebit())->toBeFalse();
});

test('isCredit method returns correct value', function () {
    $debit = AccountTransaction::factory()->debit()->make();
    $credit = AccountTransaction::factory()->credit()->make();

    expect($debit->isCredit())->toBeFalse();
    expect($credit->isCredit())->toBeTrue();
});

test('account relationship works correctly', function () {
    $account = Account::factory()->create(['name' => 'Test Account']);
    $transaction = AccountTransaction::factory()->create([
        'account_id' => $account->id,
    ]);

    expect($transaction->account->name)->toBe('Test Account');
});
