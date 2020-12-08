using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class AttackerSpawner : MonoBehaviour
{
    [SerializeField] float minSpawnDelay = 1f;
    [SerializeField] float maxSpawnDelay = 5f;
    [SerializeField] Attacker attackerPrefab;
    bool spawn = true;

    IEnumerator Start()
    {
        while (spawn)
        {
            // Randomizes the spawn point
            yield return new WaitForSeconds(Random.Range(minSpawnDelay, maxSpawnDelay));
            SpawnTitan();
        }
    }

    // Function to Spawn the Titan in
    private void SpawnTitan()
    {
        // Spawns the unit
        Instantiate(attackerPrefab, transform.position, transform.rotation);
    }

}
