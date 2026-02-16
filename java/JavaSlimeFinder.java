import java.util.Random;

public class JavaSlimeFinder {
    public static Random getSlimeRandom(long seed, int x, int z) {
        return new Random(
                seed +
                (int) (x * x * 0x4C1906) +
                (int) (x * 0x5AC0DB) +
                (int) (z * z) * 0x4307A7L +
                (int) (z * 0x5F24F) ^ 0x3AD8025FL
        );
    }
    public static boolean isSlimeChunk(long seed, int x, int z) {
        Random slimeRandom = getSlimeRandom(seed, x, z);
        return slimeRandom.nextInt(10) == 0;
    }
}