<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class InstantMeshService
{
    private HttpClientInterface $httpClient;
    private string $uploadDirectory;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $params)
    {
        $this->httpClient = $httpClient;
        $this->uploadDirectory = $params->get('model_directory'); // Dossier de stockage
    }

    public function generate3DModel(string $picturePath): ?array
    {
        $pictureData = file_get_contents($picturePath);
        $base64Picture = base64_encode($pictureData);

        try {
            $response = $this->httpClient->request('POST', 'https://huggingface.co/spaces/TencentARC/InstantMesh', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'data' => [$base64Picture],
                ],
                'timeout' => 300,
            ]);

            $data = $response->toArray();

            if (!isset($data['data'])) {
                throw new \Exception("L'API n'a pas retourné de modèles.");
            }

            return [
                'obj' => $this->saveModel($data['data'][0], 'obj'),
                'glb' => $this->saveModel($data['data'][1], 'glb'),
            ];
        } catch (\Exception $e) {
            error_log("Erreur lors de la génération du modèle 3D : " . $e->getMessage());
            return null;
        }
    }

    private function saveModel(string $base64Data, string $format): ?string
    {
        $fileName = 'model_' . uniqid() . '.' . $format;
        $filePath = $this->uploadDirectory . '/' . $fileName;
        file_put_contents($filePath, base64_decode($base64Data));

        return $fileName;
    }
}