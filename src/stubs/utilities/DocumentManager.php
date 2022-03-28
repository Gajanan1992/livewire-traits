<?php

namespace App\traits;

trait DocumentManager
{
    public function uploadDocument($document, $storagePath): array
    {
        $ext = strtolower(substr($document->getClientOriginalName(), strrpos($document->getClientOriginalName(), '.') + 1));
        $document_name = basename($document->getClientOriginalName(), '.' . $ext);
        // $document_path = $document->store('edition_documents/' . $this->edition->id);
        $document_path = $document->store($storagePath);

        return [
            'document_name' => $document_name,
            'documents' => $document_path,
        ];
    }
}
