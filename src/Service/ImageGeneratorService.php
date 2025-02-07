<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageGeneratorService
{
    private HttpClientInterface $httpClient;
    private string $uploadDirectory;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $params)
    {
        $this->httpClient = $httpClient;
        $this->uploadDirectory = $params->get('picture_directory'); // Récupère le dossier d'upload
    }

    public function generateImage(string $prompt): ?string
    {
        $url = "https://sagu7-sagu7-dating-avatar-model.hf.space/run/predict";

        try {
            $response = $this->httpClient->request('POST', $url, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => ['data' => [$prompt]],
                'timeout' => 120, // Augmente le timeout
            ]);

            $data = $response->toArray();

            if (!isset($data['data'][0])) {
                throw new \Exception("L'API n'a pas retourné d'image en Base64.");
            }

            $base64Image = $data['data'][0];

            return $this->saveImageFromBase64($base64Image);
            
        } catch (TransportExceptionInterface | ClientExceptionInterface | ServerExceptionInterface $e) {
            error_log("Erreur API : " . $e->getMessage());
            return null;
        }
    }

    private function saveImageFromBase64(string $base64Image): ?string
    {
        // 🔍 Vérifier et nettoyer la chaîne Base64
        if (str_starts_with($base64Image, 'data:image')) {
            $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $base64Image);
        }

        // 📌 Décoder le Base64 en données binaires
        $imageData = base64_decode($base64Image);
        if ($imageData === false) {
            error_log("❌ Erreur : Impossible de décoder l'image Base64.");
            return null;
        }

        // 🔖 Générer un nom de fichier unique
        $fileName = 'avatar_' . uniqid() . '.png';
        $filePath = $this->uploadDirectory . '/' . $fileName;

        // 📂 Vérifier si le dossier d'upload existe, sinon le créer
        if (!is_dir($this->uploadDirectory)) {
            mkdir($this->uploadDirectory, 0777, true);
        }

        // 💾 Enregistrer l’image en tant que fichier PNG
        if (!file_put_contents($filePath, $imageData)) {
            error_log("❌ Erreur : Échec de l'enregistrement de l'image.");
            return null;
        }

        // 📌 Vérifier si le fichier a bien été créé
        if (!file_exists($filePath)) {
            error_log("❌ Erreur : L'image n'a pas été trouvée après l'enregistrement.");
            return null;
        }

        return $fileName; // ✅ Retourne le nom du fichier enregistré
    }
}