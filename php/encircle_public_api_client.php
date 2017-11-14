<?php

$autoloader = require __DIR__ . '/vendor/autoload.php';

/**
 * @param string $ca_cert Path to Encircle CA certificate.
 *
 * @param string $client_cert Path to the client certificate. This is the
 * signed certificate generated for you based on the submitted CSR.
 *
 * @param string $client_key Path to the private key used to generate the CSR.
 * The previous 2 files consist of public information but this file should
 * never be disclosed to anyone, not even the Encircle team.
 *
 * @param string server_uri Hostname and port to connect to the API server.
 */
function main($ca_cert, $client_cert, $client_key, $server_uri) {
    $client = new Server\Public_api\PublicAPIV1Client($server_uri, [
        'credentials' => Grpc\ChannelCredentials::createSsl(
            file_get_contents($ca_cert),
            file_get_contents($client_key),
            file_get_contents($client_cert)
        ),
    ]);

    $org = getOrganization($client) or die("Could not find organization");
    $brand = getBrand($client, $org) or die("Could not find brand");

    print("Creating Claim in org {$org->getId()} with brand {$brand->getId()}\n");

    $claimInfo = new Server\Public_api\NewPropertyClaimInfo();

    // these fields are required
    $claimInfo->setOrganizationId($org->getId());
    $claimInfo->setBrandId($brand->getId());

    // one of these 3 fields should be set
    $claimInfo->setInsurerIdentifier(makeString("[PUBLIC API TEST CLAIM] INSURER IDENTIFIER"));
    $claimInfo->setAssignmentIdentifier(makeString("[PUBLIC API TEST CLAIM] ASSIGNMENT IDENTIFIER"));
    $claimInfo->setContractorIdentifier(makeString("[PUBLIC API TEST CLAIM] CONTRACTOR IDENTIFIER"));

    // all of these fields are optional and can be omitted
    $claimInfo->setPolicyholderName(makeString("[PUBLIC API TEST CLAIM] POLICYHOLDER NAME"));
    $claimInfo->setLossDetails(makeString("LOSS DETAILS"));
    $claimInfo->setFullAddress(makeString("FULL ADDRESS"));
    $claimInfo->setInsuranceCompanyName(makeString("INSURANCE COMPANY NAME"));
    $claimInfo->setAdjusterName(makeString("ADJUSTER NAME"));
    $claimInfo->setBrokerOrAgentName(makeString("BROKER OR AGENT NAME"));
    $claimInfo->setPolicyholderEmailAddress(makeString("POLICYHOLDER EMAIL ADDRESS"));
    $claimInfo->setPolicyholderPhoneNumber(makeString("POLICYHOLDER PHONE NUMBER"));
    $claimInfo->setPolicyNumber(makeString("POLICY NUMBER"));
    $claimInfo->setDateOfLoss(makeDate(2000, Server\Public_api\SymbolicDate_Month::June, 1));
    $claimInfo->setDateClaimCreated(makeDate(2000, Server\Public_api\SymbolicDate_Month::August, 15));
    $claimInfo->setDefaultDepreciation(makeDouble(0.1));
    $claimInfo->setMaxDepreciation(makeDouble(0.9));
    $claimInfo->setSalesTax(makeDouble(0.13));
    $claimInfo->setEmergencyEstimate(makeInt(150000));
    $claimInfo->setRepairEstimate(makeInt(100000));
    $claimInfo->setContentsEstimate(makeInt(5000));

    list($claimResult, $status) = $client->CreatePropertyClaim($claimInfo)->wait();
    if ($status->code != Grpc\STATUS_OK) {
        die("Could not create claim, error={$status->code}, details={$status->details}");
    }

    print("Created Claim with ID = {$claimResult->getId()}\n");
}

function getOrganization($client) {
    $orgFilter = new Server\Public_api\OrganizationFilterCriteria();
    $orgResponses = $client->ListOrganizations($orgFilter)->responses();
    foreach ($orgResponses as $org) {
        return $org;
    }
    return null;
}

function getBrand($client, $org) {
    $brandFilter = new Server\Public_api\BrandFilterCriteria();
    $brandFilter->setOrganizationId($org->getId());
    $brandResponses = $client->ListBrands($brandFilter)->responses();
    foreach ($brandResponses as $brand) {
        return $brand;
    }
    return null;
}

function makeString($str) {
    $stringValue = new Google\Protobuf\StringValue();
    $stringValue->setValue($str);
    return $stringValue;
}

function makeDate($year, $month, $day) {
    $date = new Server\Public_api\SymbolicDate();
    $date->setYear($year);
    $date->setMonth($month);
    $date->setDay($day);
    return $date;
}

function makeDouble($num) {
    $doubleValue = new Google\Protobuf\DoubleValue();
    $doubleValue->setValue($num);
    return $doubleValue;
}

function makeInt($num) {
    $intValue = new Google\Protobuf\UInt64Value();
    $intValue->setValue($num);
    return $intValue;
}

if (count($argv) < 4) {
    $usage = <<<HERE
Usage: php {$argv[0]} CA_CERT CLIENT_CERT CLIENT_KEY [SERVER_URI]

CA_CERT is the full path to the Encircle CA certificate.

CLIENT_CERT is the full path to your client certificate. This is the signed
certificate generated for you based on the submitted CSR.

CLIENT_KEY is the full path to your client PRIVATE KEY. The previous 2 files
consist of public information but the contents of this file should be kept
secret and not shared with anyone!

SERVER_URI points to `api.encircleapp.com:50051` by default but can be used to
point to a different API server.

HERE;

    die($usage);
} else {
    main(
        $argv[1],
        $argv[2],
        $argv[3],
        array_key_exists(4, $argv) ? $argv[4] : 'api.encircleapp.com:50051'
    );
}
