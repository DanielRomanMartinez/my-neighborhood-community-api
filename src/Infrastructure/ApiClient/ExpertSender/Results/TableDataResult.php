<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Results;

use Psr\Http\Message\ResponseInterface;

class TableDataResult extends ApiResult
{
    protected array $data = [];

    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response);
        $this->parse();
    }

    public function getData(): array
    {
        return $this->data;
    }

    protected function parse()
    {
        if ($this->isOk()) {
            $response = $this->removeBOM($this->response->getBody()->__toString());
            $temp = tmpfile();
            fwrite($temp, $response);
            fseek($temp, 0);
            while (($row = fgetcsv($temp)) !== false) {
                $this->data[] = $row;
            }
            fclose($temp);
        }
    }

    private function removeBOM(string $text): string
    {
        $bom = pack('H*', 'EFBBBF');

        return preg_replace("/^{$bom}/", '', $text);
    }
}
