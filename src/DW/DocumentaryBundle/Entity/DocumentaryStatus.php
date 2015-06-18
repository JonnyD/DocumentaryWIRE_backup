<?php

namespace DW\DocumentaryBundle\Entity;

abstract class DocumentaryStatus
{
    const PUBLISH = "publish";
    const DRAFT = "draft";
    const PENDING = "pending";
}