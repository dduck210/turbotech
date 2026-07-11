<?php

namespace League\OAuth2\Client\Provider {
    class AbstractProvider {
        public function getAccessToken($grant, array $options = []) {}
    }
}

namespace League\OAuth2\Client\Token {
    class AccessToken implements \Stringable {
        public function getToken() {}
        public function hasExpired() {}
        public function __toString(): string { return ''; }
    }
}

namespace League\OAuth2\Client\Grant {
    class RefreshToken {}
}
