<?php

class GOSHAP_Api
{
    private string $APP_NAME = "Sheets API PHP";
    private string $SPREADSHEET_ID = "";
    private string $SHEETNAME = "";
    private array $AUTH_CONFIG = [];

    public function __construct($config = [])
    {
        $this->APP_NAME = $config['APP_NAME'] ?? $this->APP_NAME;
        $this->SPREADSHEET_ID = $config['SPREADSHEET_ID'] ?? '';
        $this->SHEETNAME = $config['SHEETNAME'] ?? '';
        $this->AUTH_CONFIG = $config['AUTH_CONFIG'] ?? [];
    }

    private function getService()
    {
        $client = new Google_Client();
        $client->setApplicationName($this->APP_NAME);
        $client->setAuthConfig($this->AUTH_CONFIG);
        $client->addScope(Google_Service_Sheets::SPREADSHEETS);

        return new Google_Service_Sheets($client);
    }

    private function getRange()
    {
        return str_contains($this->SHEETNAME, '!')
            ? $this->SHEETNAME
            : "{$this->SHEETNAME}!A1";
    }

    public function sendRows(array $values = []): array
    {
        try {
            if (!$this->SPREADSHEET_ID) {
                throw new Exception("SPREADSHEET_ID requerido");
            }

            $service = $this->getService();

            $body = new Google_Service_Sheets_ValueRange([
                'values' => $values
            ]);

            $data = $service->spreadsheets_values->append(
                $this->SPREADSHEET_ID,
                $this->getRange(),
                $body,
                ['valueInputOption' => 'RAW']
            );

            return [
                "status" => "ok",
                "message" => "Filas insertadas 🚀",
                'data' => $data
            ];
        } catch (\Throwable $th) {
            return [
                "status" => "error",
                "message" => $th->getMessage(),
                'data' => [
                    'line' => $th->getLine(),
                    'file' => $th->getFile()
                ]
            ];
        }
    }
}