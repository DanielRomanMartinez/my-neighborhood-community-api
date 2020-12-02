<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\ValidationRules;

use App\Domain\Customer\Customer;
use App\Infrastructure\Validation\AbstractValidationRules;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Choice;

final class UpdateCustomerRules extends AbstractValidationRules
{
    public function groups(array $data): ?array
    {
        $groups = ['Default'];

        if (array_key_exists('type', $data) && $data['type'] === Customer::TYPE_COMPANY) {
            $groups[] = Customer::TYPE_COMPANY;
        }

        return $groups;
    }

    public function rules(): array
    {
        return [
            'email' => [
                new NotBlank,
                new Email(),
            ],
            'type' => [
                new NotBlank,
                new Choice([
                    Customer::TYPE_COMPANY,
                    Customer::TYPE_PARTICULAR,
                ]),
            ],
            'companySector' => [
                new Choice([
                    Customer::COMPANY_SECTOR_1,
                    Customer::COMPANY_SECTOR_2,
                    Customer::COMPANY_SECTOR_3,
                    Customer::COMPANY_SECTOR_4,
                    Customer::COMPANY_SECTOR_5,
                    Customer::COMPANY_SECTOR_6,
                    Customer::COMPANY_SECTOR_7,
                    Customer::COMPANY_SECTOR_8,
                    Customer::COMPANY_SECTOR_9,
                    Customer::COMPANY_SECTOR_10,
                    Customer::COMPANY_SECTOR_11,
                    Customer::COMPANY_SECTOR_12,
                    Customer::COMPANY_SECTOR_13,
                    Customer::COMPANY_SECTOR_14,
                    Customer::COMPANY_SECTOR_15,
                    Customer::COMPANY_SECTOR_16,
                    Customer::COMPANY_SECTOR_17,
                    Customer::COMPANY_SECTOR_18,
                    Customer::COMPANY_SECTOR_19,
                    Customer::COMPANY_SECTOR_20,
                ]),
            ],
            'companySize' => [
                new Choice([
                    Customer::COMPANY_SIZE_SMALL,
                    Customer::COMPANY_SIZE_MEDIUM,
                    Customer::COMPANY_SIZE_BIG,
                    Customer::COMPANY_SIZE_LARGE,
                ]),
            ],

        ];
    }
}
