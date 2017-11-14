<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: encircle_public_api_v1.proto

namespace Server\Public_api;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Information about a brand.
 * Brands drive the logo, org name, and other details
 * present in a claim's reports.
 * Most orgs have only one brand.
 *
 * Generated from protobuf message <code>server.public_api.BrandInfo</code>
 */
class BrandInfo extends \Google\Protobuf\Internal\Message
{
    /**
     * A unique id for this brand
     *
     * Generated from protobuf field <code>uint64 id = 1;</code>
     */
    private $id = 0;
    /**
     * The UUID of the owning organization.
     *
     * Generated from protobuf field <code>string organization_id = 2;</code>
     */
    private $organization_id = '';
    /**
     * The short name associated with this brand.
     * This is distinct from the legal name.
     *
     * Generated from protobuf field <code>.google.protobuf.StringValue short_name = 3;</code>
     */
    private $short_name = null;

    public function __construct() {
        \GPBMetadata\EncirclePublicApiV1::initOnce();
        parent::__construct();
    }

    /**
     * A unique id for this brand
     *
     * Generated from protobuf field <code>uint64 id = 1;</code>
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * A unique id for this brand
     *
     * Generated from protobuf field <code>uint64 id = 1;</code>
     * @param int|string $var
     * @return $this
     */
    public function setId($var)
    {
        GPBUtil::checkUint64($var);
        $this->id = $var;

        return $this;
    }

    /**
     * The UUID of the owning organization.
     *
     * Generated from protobuf field <code>string organization_id = 2;</code>
     * @return string
     */
    public function getOrganizationId()
    {
        return $this->organization_id;
    }

    /**
     * The UUID of the owning organization.
     *
     * Generated from protobuf field <code>string organization_id = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setOrganizationId($var)
    {
        GPBUtil::checkString($var, True);
        $this->organization_id = $var;

        return $this;
    }

    /**
     * The short name associated with this brand.
     * This is distinct from the legal name.
     *
     * Generated from protobuf field <code>.google.protobuf.StringValue short_name = 3;</code>
     * @return \Google\Protobuf\StringValue
     */
    public function getShortName()
    {
        return $this->short_name;
    }

    /**
     * The short name associated with this brand.
     * This is distinct from the legal name.
     *
     * Generated from protobuf field <code>.google.protobuf.StringValue short_name = 3;</code>
     * @param \Google\Protobuf\StringValue $var
     * @return $this
     */
    public function setShortName($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\StringValue::class);
        $this->short_name = $var;

        return $this;
    }

}
