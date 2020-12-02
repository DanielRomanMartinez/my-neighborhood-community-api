SHELL = /bin/bash

.PHONY: help
help: ## Display this help message
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

# 🐘 Composer
.PHONY: composer-install
composer-install: CMD=install ## Install composer dependencies

.PHONY: composer-update
composer-update: CMD=update ## Update composer dependencies

composer composer-install composer-update:
	@docker exec -it --user 1000:1000 legal.api sh -c "composer $(CMD) --no-interaction"

.PHONY: env-download
env-download: ## Download enviroment file from S3.
		$(info  ************ Downloading env file to S3...)
		@env=$(e);if [ "$$env" == "prod" ]; then \
			AWS_PROFILE=legalcontracts-prod aws s3 cp s3://legal-contracts-config/lawdistrict.com/prod/api/.env .env.prod; \
		elif [ "$$env" == "stage" ]; then \
			AWS_PROFILE=legalcontracts-dev aws s3 cp s3://legal-contracts-config/lawdistrict.com/stage/api/.env .env.stage; \
		elif [ "$$env" == "stage2" ]; then \
			AWS_PROFILE=legalcontracts-dev aws s3 cp s3://legal-contracts-config/lawdistrict.com/stage2/api/.env .env.stage2; \
		else \
			AWS_PROFILE=legalcontracts-dev aws s3 cp s3://legal-contracts-config/lawdistrict.com/local/api/.env .; \
		fi \

.PHONY: env-upload
env-upload: ## Upload enviroment file to S3
		$(info  ************ Uploading env file to S3...)
		@env=$(e);if [ "$$env" == "prod" ]; then \
			AWS_PROFILE=legalcontracts-prod aws s3 cp .env.prod s3://legal-contracts-config/lawdistrict.com/prod/api/.env; \
		elif [ "$$env" == "stage" ]; then \
			AWS_PROFILE=legalcontracts-dev aws s3 cp .env.stage s3://legal-contracts-config/lawdistrict.com/stage/api/.env; \
		elif [ "$$env" == "stage2" ]; then \
			AWS_PROFILE=legalcontracts-dev aws s3 cp .env.stage2 s3://legal-contracts-config/lawdistrict.com/stage2/api/.env; \
		else \
			AWS_PROFILE=legalcontracts-dev aws s3 cp .env s3://legal-contracts-config/lawdistrict.com/local/api/.env; \
		fi \


