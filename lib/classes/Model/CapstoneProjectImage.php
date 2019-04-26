<?php
namespace Model;

use Util\IdGenerator;

/**
 * Represents an image (JPEG, PNG, etc.) associated with a capstone project. Projects can have multiple
 * images.
 */
class CapstoneProjectImage {

    /** @var string */
    private $id;
    /** @var CapstoneProject */
    private $project;
    /** @var string */
    private $name;
    /** @var boolean */
    private $isDefault;

    public function __construct($id = null) {
        if ($id == null) {
            $id = IdGenerator::generateSecureUniqueId();
        }
        $this->setId($id);
        $this->setIsDefault(false);
    }

    /**
     * Set the ID for the image
     *
     * @param string $id
     * @return self 
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the ID
     *
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set the project the image belongs to
     *
     * @param CapstoneProject $project
     * @return self
     */
    public function setProject($project) {
        $this->project = $project;
        return $this;
    }

    /**
     * Get the project the image belongs to
     *
     * @return CapstoneProject
     */
    public function getProject() {
        return $this->project;
    }

    /**
     * Sets the name of the image file
     *
     * @param string $name
     * @return self
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns the name of the image file
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets whether the image is the default or not.
     *
     * @param boolean $isDefault
     * @return self
     */
    public function setIsDefault($isDefault) {
        $this->isDefault = $isDefault;
        return $this;
    }

    /**
     * Tells whether the image is the default or not.
     *
     * @return boolean
     */
    public function getIsDefault() {
        return $this->isDefault;
    }
}