# Onboarding

## Conventions Git

Nommage des branches, messages de commit, merge requests et relecture

### Branches

Format : `type/description-en-kebab-case`, avec optionnellement un numéro de ticket en préfixe.

```
feat/ma-feature
feat/HPSOQ336-ajout-filtre-recherche
fix/prevent-grandest-inputs-in-nomad-profile
chore/update-dependencies
```

| Élément | Format | Exemple |
|---|---|---|
| Type | Même préfixe que les commits | `feat`, `fix`, `chore` |
| Ticket | Optionnel, en majuscules | `HPSOQ336` |
| Description | kebab-case, en anglais | `add-search-filter` |

### Commits

Format Conventional Commits :

```
type(scope): description courte
```

- **scope** : optionnel, le module ou zone concernée (minuscules)
- **description** : impératif, minuscules, sans point final, max 50 caractères

```
feat(auth): add password reset functionality
fix(booking): resolve double-booking race condition
refactor(api): extract validation into middleware
docs(readme): update installation instructions
chore(deps): bump axios to 1.6.0
```

#### Préfixes

| Préfixe | Usage |
|---|---|
| `feat` | Nouvelle fonctionnalité |
| `fix` | Correction de bug |
| `refactor` | Refactoring sans changement fonctionnel |
| `chore` | Tâche sans rapport avec le code métier (dépendances, config) |
| `docs` | Documentation |
| `style` | Formatage, espaces, points-virgules (pas de changement fonctionnel) |
| `test` | Ajout ou correction de tests |
| `perf` | Amélioration de performance |
| `ci` | Configuration CI/CD |
| `build` | Système de build ou dépendances externes |
| `revert` | Annulation d'un commit précédent |

### Merge requests

La description des merge requests s'écrit en français ; le titre, les commits et les noms de branche restent en anglais.

- **Titre** : en anglais, celui du commit principal, au même format (`type(scope): description`, 50 caractères au plus sans compter le préfixe), précédé du ticket entre crochets quand la branche porte un numéro de ticket : `[HPSOQ336] feat(search): add search filter`.
- **Description** courte, dans cet ordre : le problème, ce qui change, la preuve (captures avant/après, sortie de commande, test de non-régression), les détails utiles à la relecture, puis « En bref » en deux ou trois phrases.
- Rien qui ne soit pas dans la branche, aucune mention d'IA (`Co-Authored-By`, « Generated with… »), jamais de squash à la fusion.

### Relire une MR

Les remarques de relecture suivent Conventional Comments, qu'elles viennent du skill `/gitlab-review` ou d'une relecture à la main. Chaque remarque commence par une étiquette et une décoration :

```
étiquette (décoration): `fichier:ligne` constat et correction
```

La position `fichier:ligne` est facultative pour une remarque posée directement sur une ligne de la MR.

| Étiquette | Usage |
|---|---|
| `issue` | Un défaut à corriger |
| `suggestion` | Une meilleure façon de faire |
| `question` | Un point à clarifier |
| `nitpick` | Un détail de style, sans conséquence |
| `todo` | Un petit changement nécessaire |
| `praise` | Ce qui est bien fait, quand c'est vrai |

La décoration dit si la remarque bloque la fusion : `(bloquant)` ou `(non bloquant)`, traductions de `(blocking)` et `(non-blocking)`.

```
issue (bloquant): `src/order/invoice.ts:42` Le numéro de facture est calculé avant le verrou : deux ventes simultanées reçoivent le même numéro. Prendre le numéro dans la transaction.
```

## Skills Claude Code

Trois skills d'équipe, publiés dans le dépôt `ressources/doc` (dossier `skills/`) :

| Skill | Usage |
|---|---|
| `/commit` | Analyse les changements git en cours et propose un message de commit conforme, en anglais. Il ne commite que si vous ou la tâche en cours le demandez. Il se déclenche aussi seul dès qu'un commit se prépare. Dans un monorepo, le scope se déduit des chemins modifiés. |
| `/pr-writing` | Rédige le titre et la description d'une merge request selon les règles ci-dessus, les montre et attend votre accord avant de créer ou modifier la MR avec `glab api`. |
| `/gitlab-review` | Relit une MR GitLab (numéro ou URL) avec la commande intégrée `/code-review` de Claude Code, au niveau d'effort `high` par défaut (vous pouvez en donner un autre, sauf `ultra`), puis rédige un seul commentaire au format Conventional Comments. Il le montre et attend votre accord avant de le poster ; il n'approuve ni ne fusionne jamais. |
