<?php
namespace Application\Acl;

/**
 * Rebuildable interface for classes supporting rebuilding of acl
 */
interface Rebuildable
{

    /**
     * Rebuilds Acl
     */
    public function rebuildAcl();
}
