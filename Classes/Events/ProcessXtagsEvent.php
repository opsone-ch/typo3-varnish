<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012  Andri Steiner  <team@opsone.ch>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace Opsone\Varnish\Events;

class ProcessXtagsEvent implements \Psr\EventDispatcher\StoppableEventInterface
{
    private array $xtags;
    private bool $isStopped;

    /**
     * Creates an Xtag event
     * @param array $xtags
     * @return void
     */
    public function __construct(array $xtags)
    {
        //Flip the array so it's easier to manipulate the individual keys
        $this->xtags = array_flip($xtags);
        $this->isStopped = false;
    }

    /**
     * Prevent other listners from handling this event.
     *
     * You will properly never need to use this.
     *
     * @return void
     */
    public function stopPropagation()
    {
        $this->isStopped = true;
    }

    public function isPropagationStopped(): bool
    {
        return $this->isStopped;
    }

    /**
     * Check if a tag is set
     * @param string $tag
     * @return bool
     */
    public function hasTag(string $tag): bool
    {
        return isset($this->xtags[$tag]);
    }

    /**
     * removes a tag
     * @param string $tag
     * @return bool returns false if the tag didn't exist, true otherwise
     */
    public function removeTag(string $tag): bool
    {
        if (!$this->hasTag($tag)) {
            return false;
        }
        unset($this->xtags[$tag]);
        return true;
    }

    /**
     * Adds a tag
     * @param string $tag
     * @return bool returns false if the tag already exists, false otherwise
     */
    public function addTag(string $tag): bool
    {
        if ($this->hasTag($tag) {
            return false;
        }
        $this->xtags[$tag] = true;
        return true;
    }

    /**
     * Short hand for removeTag,addTag
     * @param string $originalTag the tag to remove
     * @param string $newTag the tag to add
     * @return bool true if the originalTag was removed and the new one added, false otherwise
     */
    public function renameTag(string $originalTag, string $newTag): bool
    {
        if (!$this->removeTag($originalTag)) {
            return false;
        }
        return $this->addTag($newTag);
    }

    /**
     * Returns the complete and possibly changed list of tags
     * @return array
     */
    public function getXtags(): array
    {
        //Since we flipped the array we need to return the keys here - the values are meaningless to us.
        return array_keys($this->xtags);
    }
}
