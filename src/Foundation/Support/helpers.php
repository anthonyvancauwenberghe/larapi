<?php


if (!function_exists('getAuthenticatedUserId')) {
    function getAuthenticatedUserId()
    {
        return getAuthenticatedUser()->id;
    }
}

if (!function_exists('getAuthenticatedUser')) {

    /**
     * @return \Modules\User\Entities\User
     */
    function getAuthenticatedUser(): \Illuminate\Contracts\Auth\Authenticatable
    {
        if (Auth::user() !== null) {
            return Auth::user();
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('no authorized user');
        }
    }
}

if (!function_exists('getShortClassName')) {
    function getShortClassName($class)
    {
        if (!is_string($class)) {
            $class = get_class($class);
        }

        return substr(strrchr($class, '\\'), 1);
    }
}

if (!function_exists('getRandomArrayElement')) {
    function getRandomArrayElement(array $array)
    {
        if (empty($array)) {
            return;
        }
        $randomIndex = random_int(0, count($array) - 1);

        return $array[$randomIndex];
    }
}
if (!function_exists('createArrayFromFactory')) {
    function createArrayFromFactory(string $modelClass, $amount = 1, ?string $state = null)
    {
        if ($amount < 1) {
            return false;
        }

        $factory = factory($modelClass, $amount);

        if ($state !== null) {
            $factory->state($state);
        }

        return $factory->raw();
    }
}

if (!function_exists('createFromFactory')) {
    function createFromFactory(string $modelClass, ?string $state = null)
    {
        $factory = factory($modelClass);

        if ($state !== null) {
            $factory->state($state);
        }

        return $factory->raw();
    }
}

if (!function_exists('classImplementsInterface')) {
    function classImplementsInterface($class, $interface)
    {
        return in_array($interface, class_implements($class));
    }
}

if (!function_exists('classUsesTrait')) {
    function classUsesTrait($class, string $trait)
    {
        if (!is_string($class)) {
            $class = get_class($class);
        }

        $traits = array_flip(class_uses_recursive($class));

        return isset($traits[$trait]);
    }
}
