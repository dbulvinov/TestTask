<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

use Vich\UploaderBundle\Entity\File as EmbeddableFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="images")
 * @Vich\Uploadable
 */
class Image
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(options={"default":""})
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var EmbeddableFile
     *
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     */
    protected $image;

    /**
     * @var File|UploadedFile
     *
     * @Assert\NotBlank(groups={"new"})
     * @Assert\File()
     * @Vich\UploadableField(mapping="images", fileNameProperty="name", originalName="originalName", mimeType="mimeType", size="size")
     */
    protected $uploadedFile;

    /**
     * @var Album
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Album")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $album;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    public function __construct()
    {
        $this->title = '';
        $this->image = new EmbeddableFile();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return EmbeddableFile
     */
    public function getImage(): EmbeddableFile
    {
        return $this->image;
    }

    /**
     * @param EmbeddableFile $image
     */
    public function setImage(EmbeddableFile $image): void
    {
        $this->image = $image;
    }

    /**
     * @return Album
     */
    public function getAlbum(): Album
    {
        return $this->album;
    }

    /**
     * @param Album $album
     */
    public function setAlbum(Album $album): void
    {
        $this->album = $album;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return File|UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * @param File|UploadedFile $uploadedFile
     */
    public function setUploadedFile(File $uploadedFile = null)
    {
        $this->uploadedFile = $uploadedFile;
        if ($this->uploadedFile instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }
    }

    public function setFileFixture($filePath, $originalName)
    {
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'image-');
        copy($filePath, $tmpFilePath);
        $this->setUploadedFile(new UploadedFile($tmpFilePath, $originalName, null, null, null, true));
    }
}