#!/usr/bin/env python

from google.protobuf.wrappers_pb2 import StringValue, DoubleValue, UInt64Value
import grpc

import os
import os.path
import subprocess
import sys

def generate_bindings():
    script_dir = os.path.dirname(os.path.realpath(__file__))
    process = subprocess.Popen(
        (
            'python',
            '-m'
            'grpc_tools.protoc',
            '-I..',
            '--python_out=.',
            '--grpc_python_out=.',
            '../encircle_public_api_v1.proto',
        ),
        cwd=script_dir,
    )
    process.wait()

def main(ca_cert, client_cert, client_key, server_uri='api.encircleapp.com:50051'):
    from encircle_public_api_v1_pb2_grpc import PublicAPIV1Stub
    from encircle_public_api_v1_pb2 import (
        BrandFilterCriteria,
        NewPropertyClaimInfo,
        OrganizationFilterCriteria,
        PropertyClaimDocumentFilterCriteria,
        PropertyClaimFilterCriteria,
        PropertyClaimId,
        PropertyClaimPictureFilterCriteria,
        SymbolicDate,
    )

    with open(ca_cert, 'rb') as ca_cert_file,\
            open(client_cert, 'rb') as client_cert_file,\
            open(client_key, 'rb') as client_key_file:

        client_credentials = grpc.ssl_channel_credentials(
            root_certificates=ca_cert_file.read(),
            certificate_chain=client_cert_file.read(),
            private_key=client_key_file.read(),
        )

    channel = grpc.secure_channel(server_uri, client_credentials)

    stub = PublicAPIV1Stub(channel)

    # Lists all organizations
    for organization in stub.ListOrganizations(OrganizationFilterCriteria()):
        print("FOUND ORGANIZATION: {!r}".format(organization.id))

        # Lists all brands for a given organization
        for brand in stub.ListBrands(BrandFilterCriteria(organization_id=organization.id)):
            print("FOUND BRAND: {!r}".format(brand))

        # Lists all claims for a given organization
        for (i, claim) in enumerate(stub.ListPropertyClaims(PropertyClaimFilterCriteria(organization_id=organization.id))):
            print("FOUND CLAIM: {!r}".format(claim))

            # Lists all pictures in a given claim
            for p in stub.ListPropertyClaimPictures(PropertyClaimPictureFilterCriteria(claim_id=claim.id)):
                print("FOUND PICTURE: {!r}".format(p.download_uri))

            # Lists all rendered documents in a given claim
            for p in stub.ListPropertyClaimDocuments(PropertyClaimDocumentFilterCriteria(claim_id=claim.id)):
                print("FOUND DOCUMENT: {!r}".format(p.download_uri))

            if i >= 10:
                break

    # This example fills all fields, but most fields can be left out (and will either be blank or given a reasonable default).
    to_create = NewPropertyClaimInfo(
        organization_id=organization.id,
        brand_id=brand.id,
        insurer_identifier=StringValue(
            value="[PUBLIC API TEST CLAIM] INSURER IDENTIFIER",
        ),
        assignment_identifier=StringValue(
            value="[PUBLIC API TEST CLAIM] ASSIGNMENT IDENTIFIER",
        ),
        contractor_identifier=StringValue(
            value="[PUBLIC API TEST CLAIM] CONTRACTOR IDENTIFIER",
        ),
        policyholder_name=StringValue(
            value="[PUBLIC API TEST CLAIM] POLICYHOLDER NAME",
        ),
        loss_details=StringValue(
            value="LOSS DETAILS",
        ),
        full_address=StringValue(
            value="FULL ADDRESS",
        ),
        insurance_company_name=StringValue(
            value="INSURANCE COMPANY NAME",
        ),
        adjuster_name=StringValue(
            value="ADJUSTER NAME",
        ),
        broker_or_agent_name=StringValue(
            value="BROKER OR AGENT NAME",
        ),
        policyholder_email_address=StringValue(
            value="POLICYHOLDER EMAIL ADDRESS",
        ),
        policyholder_phone_number=StringValue(
            value="POLICYHOLDER PHONE NUMBER",
        ),
        policy_number=StringValue(
            value="POLICY NUMBER",
        ),
        date_of_loss=SymbolicDate(
            year=2000,
            month=SymbolicDate.June,
            day=1,
        ),
        date_claim_created=SymbolicDate(
            year=1999,
            month=SymbolicDate.August,
            day=15,
        ),
        default_depreciation=DoubleValue(
            value=0.1,
        ),
        max_depreciation=DoubleValue(
            value=0.9,
        ),
        sales_tax=DoubleValue(
            value=0.13,
        ),
        emergency_estimate=UInt64Value(
            value=150000,
        ),
        repair_estimate=UInt64Value(
            value=100000,
        ),
        contents_estimate=UInt64Value(
            value=5000,
        )
    )

    created = stub.CreatePropertyClaim(to_create)

    print("CREATED CLAIM: {}".format(created))

if __name__ == '__main__':
    generate_bindings()
    if len(sys.argv) not in (4, 5):
        print("USAGE: ./encircle_public_api_client.py FULL_PATH_TO_CA_CERT FULL_PATH_TO_CLIENT_CERT FULL_PATH_TO_CLIENT_KEY [SERVER_URI]")

    main(*sys.argv[1:])
