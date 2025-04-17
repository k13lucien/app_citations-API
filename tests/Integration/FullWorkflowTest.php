<?php

use PHPUnit\Framework\TestCase;

class FullWorkflowTest extends TestCase
{
    private $baseUrl = "http://localhost:8000/api";
    private $token;
    private $userId;
    private $citationId;

    public function testFullUserWorkflow()
    {
        // 1. Enregistrement d’un nouvel utilisateur
        $response = $this->post('/utilisateurs/register', [
            'name' => 'Testeur',
            'surname' => '1',
            'email' => 'testeur@example.com',
            'password' => 'password123'
        ]);

        $this->assertEquals(201, $response['status']);
        $this->userId = $response['body']['id'] ?? null;

        // 2. Connexion et récupération du token
        $login = $this->post('/utilisateurs/login', [
            'email' => 'testeur@example.com',
            'password' => 'password123'
        ]);

        $this->assertEquals(200, $login['status']);
        $this->token = $login['body']['token'] ?? null;

        $this->assertNotEmpty($this->token);

        // 3. Création d’une citation
        $citation = $this->post('/citations', [
            'name' => 'vivre',
            'content' => 'La persévérance est la clé.',
            'utilisateur_id' => 9,
            'categorie_id' => 14
        ]);

        $this->assertEquals(201, $citation['status']);
        $this->citationId = $citation['body']['id'] ?? null;

        // 4. Ajout d’un like
        $like = $this->post("/citations/{$this->citationId}/like");
        $this->assertEquals(200, $like['status']);

        // 5. Ajout d’une vue
        $vue = $this->post("/citations/{$this->citationId}/vue");
        $this->assertEquals(200, $vue['status']);

        // 6. Suppression de la citation
        $delete = $this->delete("/citations/{$this->citationId}");
        $this->assertEquals(200, $delete['status']);

        // 7. Suppression de l’utilisateur
        $deleteUser = $this->delete("/utilisateurs/{$this->userId}");
        $this->assertEquals(200, $deleteUser['status']);
    }

    private function post($uri, $data = [])
    {
        return $this->request('POST', $uri, $data);
    }

    private function delete($uri)
    {
        return $this->request('DELETE', $uri);
    }

    private function request($method, $uri, $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $headers = ['Content-Type: application/json'];
        if ($this->token) {
            $headers[] = "Authorization: Bearer {$this->token}";
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status' => $status,
            'body' => json_decode($response, true)
        ];
    }
}
