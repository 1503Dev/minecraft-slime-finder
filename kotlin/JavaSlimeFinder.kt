import java.util.Random

object JavaSlimeFinder {
    fun getSlimeRandom(seed: Long, x: Int, z: Int): Random {
        return Random(
            seed +
            (x * x * 0x4C1906).toLong() +
            (x * 0x5AC0DB).toLong() +
            (z * z).toLong() * 0x4307A7L +
            (z * 0x5F24F).toLong() xor 0x3AD8025FL.toLong()
        )
    }

    fun isSlimeChunk(seed: Long, x: Int, z: Int): Boolean {
        val slimeRandom = getSlimeRandom(seed, x, z)
        return slimeRandom.nextInt(10) == 0
    }
}