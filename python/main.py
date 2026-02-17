from bedrock_slime_finder import BedrockSlimeFinder
from java_slime_finder import JavaSlimeFinder

if __name__ == '__main__':
    edition = 'java'
    x = 114514
    z = 11451
    seed = 1503 # Only for Java Edition

    if edition == 'bedrock':
        rs = BedrockSlimeFinder.get_slime_random_seed(x, z)
        print(f'Random Seed: {rs}')
        rez = BedrockSlimeFinder.is_slime_chunk(x, z)
        print(f'Is Slime Chunk: {rez}')
    elif edition == 'java':
        rs = JavaSlimeFinder.get_slime_random_seed(seed, x, z)
        print(f'Random Seed: {rs}')
        rez = JavaSlimeFinder.is_slime_chunk(seed, x, z)
        print(f'Is Slime Chunk: {rez}')