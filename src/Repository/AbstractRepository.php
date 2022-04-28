<?php

namespace App\Repository;

class AbstractRepository 
{
    private $lastModified;

    public function alphabeticalSorting(array $data): array
    {
        usort($data, function($x, $y) {
            return $x->getName() <=> $y->getName();
        });

        return $data;
    }

    public function setLastModified(string $filePath): void
    {
        $lms = filemtime($filePath);

        if (!$lms) {
            throw new \RuntimeException('Could not read lastmod.');
        }
        $this->lastModified = \DateTimeImmutable::createFromFormat('U', $lms);
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getLastModified(): \DateTimeImmutable
    {
        return $this->lastModified;
    }
}