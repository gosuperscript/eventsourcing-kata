<?php

use Robertbaelde\Eventsourcing\ConsumptionTest\AccountCredited;
use Robertbaelde\Eventsourcing\ConsumptionTest\AccountDebited;
use Robertbaelde\Eventsourcing\ConsumptionTest\BankAccount;
use Robertbaelde\Eventsourcing\ConsumptionTest\BankAccountNumber;
use Robertbaelde\Eventsourcing\ConsumptionTest\BankAccountRepository;
use Robertbaelde\Eventsourcing\ConsumptionTest\CouldntDebitBecauseInsufficientFunds;
use Robertbaelde\Eventsourcing\Persistence\FileMessageRepository;
use Robertbaelde\Eventsourcing\Persistence\MessageIdGenerator;
use Robertbaelde\Eventsourcing\Serialization\ConfiguredMappingMapper;
use Robertbaelde\Eventsourcing\Serialization\DefaultEventWrapper;
use Robertbaelde\Eventsourcing\Serialization\DefaultMessageSerializer;

require __DIR__.'/vendor/autoload.php';

echo "Bank account number: ";
$fin = fopen ("php://stdin","r");
$bankAccount = BankAccountNumber::fromString(stripNewline(fgets($fin)));

echo "Action to take: <debit,credit>: ";
$fin = fopen ("php://stdin","r");
$action = stripNewline(fgets($fin));
if($action !== 'debit' && $action !== 'credit'){
    throw new Exception('Invalid action');
}

echo "Amount to {$action}: ";
$fin = fopen ("php://stdin","r");
$amount = (int) stripNewline(fgets($fin));
if($amount <= 0){
    throw new Exception('Invalid amount, must be positive');
}

echo "are you sure you want to {$action} bank account {$bankAccount->toString()} with {$amount}? (y/n): ";

$fin = fopen ("php://stdin","r");
$confirm = stripNewline(fgets($fin));
if($confirm !== 'y'){
    echo "aborted";
    return;
}

$aggregateIdMap = new ConfiguredMappingMapper([BankAccountNumber::class => 'bankAccountNumber']);
$eventsMap = new ConfiguredMappingMapper([
    AccountCredited::class => 'AccountCredited',
    AccountDebited::class => 'AccountDebited',
    CouldntDebitBecauseInsufficientFunds::class => 'CouldntDebitBecauseInsufficientFunds'
]);

$bankAccountRepository = new BankAccountRepository(
    messageRepository: new FileMessageRepository('storage/', $aggregateIdMap),
    mapEventToMessageTransformer: new DefaultEventWrapper(new MessageIdGenerator()),
    serializeMessage: new DefaultMessageSerializer(
        eventTypeMapper: $eventsMap,
        aggregateRootIdTypeMapper: $aggregateIdMap
    ),
);

$bankAccount = $bankAccountRepository->retrieveByAggregateId($bankAccount);
try {
    if($action === 'credit'){
        $bankAccount->credit($amount);
    } else {
        $bankAccount->debit($amount);
    }
} catch (\Robertbaelde\Eventsourcing\ConsumptionTest\SorryCouldntDebitBecauseInsufficientFunds $exception) {
    echo $exception->getMessage();
    return;
} finally {
    $bankAccountRepository->persist($bankAccount);
}

echo "done";


function stripNewline(string $string)
{
    return str_replace(array("\r", "\n"), '', $string);
}
