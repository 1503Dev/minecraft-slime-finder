<?php

class JavaSlimeFinder {
    private const MULTIPLIER = 25214903917;
    private const MASK_48 = 281474976710655;
    private const ADDEND = 11;

    private static function toInt32($value) {
        return self::asIntN(32, $value);
    }

    private static function asIntN(int $bits, $value) {
        $val = gmp_init((string)$value);
        $mask = gmp_sub(gmp_pow(2, $bits), 1);
        $val = gmp_and($val, $mask);
        
        $threshold = gmp_pow(2, $bits - 1);
        if (gmp_cmp($val, $threshold) >= 0) {
            $val = gmp_sub($val, gmp_mul(2, $threshold));
        }
        return $val;
    }

    private static function next($seed, int $bits) {
        $multiplier = gmp_init(self::MULTIPLIER);
        $addend = gmp_init(self::ADDEND);
        $mask48 = gmp_init(self::MASK_48);
        
        $seed = gmp_and(gmp_add(gmp_mul($seed, $multiplier), $addend), $mask48);
        $shift = gmp_pow(2, 48 - $bits);
        return [$seed, gmp_div_q($seed, $shift)];
    }

    private static function nextInt($seed, $bound) {
        if ($bound <= 0) {
            throw new Exception("bound must be positive");
        }

        $boundBig = gmp_init((string)$bound);

        if (gmp_cmp(gmp_and($boundBig, gmp_sub($boundBig, 1)), 0) === 0) {
            $res = self::next($seed, 31);
            $mul = gmp_mul($boundBig, $res[1]);
            $div = gmp_div_q($mul, gmp_pow(2, 31));
            return [$res[0], $div];
        }

        $u = 0;
        $v = 0;
        $currentSeed = $seed;
        do {
            $res = self::next($currentSeed, 31);
            $currentSeed = $res[0];
            $u = $res[1];
            $v = gmp_mod($u, $boundBig);
        } while (gmp_cmp(gmp_add(gmp_sub($u, $v), gmp_sub($boundBig, 1)), 0) < 0);

        return [$currentSeed, $v];
    }

    public static function getSlimeRandomSeed($worldSeed, $x, $z) {
        $ws = self::asIntN(64, $worldSeed);
        $cx = $x;
        $cz = $z;

        $term1 = self::toInt32($cx * $cx * 0x4c1906);
        $term2 = self::toInt32($cx * 0x5ac0db);
        $term3 = self::toInt32($cz * $cz) * 0x4307a7;
        $term4 = self::toInt32($cz * 0x5f24f);
        $xorVal = 0x3ad8025f;

        $seed = $ws + $term1 + $term2 + $term3 + $term4;
        $seed = $seed ^ $xorVal;

        return $seed;
    }

    public static function isSlimeChunk($worldSeed, $x, $z) {
        $seed = self::getSlimeRandomSeed($worldSeed, $x, $z);
        $seed = self::asIntN(64, $seed);

        $multiplier = gmp_init(self::MULTIPLIER);
        $currentSeed = gmp_and(gmp_xor($seed, $multiplier), gmp_init(self::MASK_48));
        $currentSeed = self::asIntN(48, $currentSeed);

        $res = self::nextInt($currentSeed, 10);

        return gmp_cmp($res[1], 0) === 0;
    }
}