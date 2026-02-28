# Requêtes SQL — Aide-mémoire

## Requêtes statiques

### 1. Nombre de tickets par état

Compte le nombre total de tickets regroupés par leur état (ouvert, en cours, fermé, etc.).

```sql
SELECT etat, COUNT(*) AS nombre_tickets
FROM tickets
GROUP BY etat;
```

**Exemple de résultat :**

| etat    | nombre_tickets |
|---------|----------------|
| ouvert  | 12             |
| en_cours| 7              |
| ferme   | 25             |

---

### 2. Liste des techniciens avec leur nombre de tickets assignés

Affiche chaque technicien et le nombre de tickets qui lui sont assignés. Les techniciens sans ticket apparaissent avec 0.

```sql
SELECT t.id, t.nom, t.email, t.specialite, COUNT(tk.id) AS nombre_tickets
FROM techniciens t
LEFT JOIN tickets tk ON tk.technicien_id = t.id
GROUP BY t.id, t.nom, t.email, t.specialite
ORDER BY nombre_tickets DESC;
```

**Exemple de résultat :**

| id | nom           | email              | specialite | nombre_tickets |
|----|---------------|--------------------|------------|----------------|
| 3  | Dupont Marc   | marc@example.com   | réseau     | 8              |
| 1  | Leroy Julie   | julie@example.com  | hardware   | 5              |
| 7  | Moreau Paul   | paul@example.com   | logiciel   | 0              |

---

### 3. Tickets non assignés et encore ouverts

Liste tous les tickets qui n'ont pas encore été assignés à un technicien et dont l'état est « ouvert ».

```sql
SELECT id, titre, description, priorite, created_at
FROM tickets
WHERE technicien_id IS NULL
  AND etat = 'ouvert'
ORDER BY created_at ASC;
```

**Exemple de résultat :**

| id | titre                  | priorite | created_at          |
|----|------------------------|----------|---------------------|
| 14 | Imprimante en panne    | haute    | 2026-02-20 09:15:00 |
| 22 | Accès VPN impossible   | moyenne  | 2026-02-25 14:30:00 |

---

## Requêtes dynamiques (avec paramètres)

### 1. Tickets d'un technicien donné avec son nom complet

Retourne tous les tickets assignés à un technicien spécifique, en affichant le nom du technicien via une jointure.

```sql
SELECT tk.id, tk.titre, tk.etat, tk.priorite, tk.created_at, t.nom AS technicien_nom
FROM tickets tk
INNER JOIN techniciens t ON t.id = tk.technicien_id
WHERE tk.technicien_id = :technicien_id
ORDER BY tk.created_at DESC;
```

**Exemple concret** (technicien_id = 3) :

```sql
SELECT tk.id, tk.titre, tk.etat, tk.priorite, tk.created_at, t.nom AS technicien_nom
FROM tickets tk
INNER JOIN techniciens t ON t.id = tk.technicien_id
WHERE tk.technicien_id = 3
ORDER BY tk.created_at DESC;
```

| id | titre                     | etat     | priorite | created_at          | technicien_nom |
|----|---------------------------|----------|----------|---------------------|----------------|
| 5  | Écran bleu au démarrage   | en_cours | haute    | 2026-02-27 11:00:00 | Dupont Marc    |
| 9  | Mise à jour Windows       | ouvert   | basse    | 2026-02-22 08:45:00 | Dupont Marc    |

---

### 2. Tickets créés entre deux dates

Retourne tous les tickets créés dans un intervalle de dates donné.

```sql
SELECT id, titre, etat, priorite, technicien_id, created_at
FROM tickets
WHERE created_at BETWEEN :date_debut AND :date_fin
ORDER BY created_at ASC;
```

**Exemple concret** (du 1er au 28 février 2026) :

```sql
SELECT id, titre, etat, priorite, technicien_id, created_at
FROM tickets
WHERE created_at BETWEEN '2026-02-01 00:00:00' AND '2026-02-28 23:59:59'
ORDER BY created_at ASC;
```

| id | titre                    | etat   | priorite | technicien_id | created_at          |
|----|--------------------------|--------|----------|---------------|---------------------|
| 10 | Problème de messagerie   | ferme  | moyenne  | 1             | 2026-02-03 10:20:00 |
| 14 | Imprimante en panne      | ouvert | haute    | NULL          | 2026-02-20 09:15:00 |

---

### 3. Recherche de tickets par mot-clé

Recherche dans le titre et la description des tickets un mot-clé donné.

```sql
SELECT id, titre, description, etat, priorite, created_at
FROM tickets
WHERE titre LIKE '%' || :keyword || '%'
   OR description LIKE '%' || :keyword || '%'
ORDER BY created_at DESC;
```

**Exemple concret** (keyword = « imprimante ») :

```sql
SELECT id, titre, description, etat, priorite, created_at
FROM tickets
WHERE titre LIKE '%imprimante%'
   OR description LIKE '%imprimante%'
ORDER BY created_at DESC;
```

| id | titre                    | etat   | priorite | created_at          |
|----|--------------------------|--------|----------|---------------------|
| 14 | Imprimante en panne      | ouvert | haute    | 2026-02-20 09:15:00 |
| 6  | Cartouche imprimante HP  | ferme  | basse    | 2026-01-15 16:40:00 |
