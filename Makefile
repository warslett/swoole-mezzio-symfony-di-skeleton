RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
$(eval $(RUN_ARGS):;@:)

.PHONY: composer
composer:
	@docker-compose run --rm app composer "$(RUN_ARGS)"
