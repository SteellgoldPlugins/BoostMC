

# Museum

Big Core for Museum's Server includes:
* PocketMine-MP plugin
* Discord Bot in JavaScript (**Drowned Zombie#7788**)
* Web Page for the server

## Installation
```bash
git clone --recursive branch=master https://github.com/Steellgold/Museum
```

## Todo List:
### PocketMine-MP
__Moderator Features__:
- TODO
---
__Faction Features__:

Ranks:
 - The permissions are the same if you click on the form (`/f form`)
	 * Ranks:
		 * 0: Recrue
		 * 1: Member (**Symbol:** * )
		 * 2: Officier (**Symbol:** ** )
		 * 3: Leader  (**Symbol:** *** )
* Table of features:
	- ✅: Done
	- ❌: Not done
	- ⏰: Still in development

| Command or Feature| Description | Status  | Rank  | Aliases |
|--|--|--|--|--|
| `/f placechest`| Place the faction chest | ❌ | 3 | [`/f pc`] |
| `/f configchest`| Place the faction chest | ❌ | 3 | [`/f cg`] |
| `/f unclaim`| Unprotect a zone | ✅ | >= 2 | [`/f uc`] |
| `/f claim`| Protect a zone for that other factions can't break or put a block in it | ✅ | >= 2| [`/f cl`] |
| `/f create`| Create a faction (Cost: 500$) | ⏰ | < 0 | [`/f`] |
| `/f disband`| Delete your faction and claims actives | ❌ | 3 | [`/f di`] |
| `/f invite`| Invite a player into your faction | ✅ | >= 2 | [`/f in`] |
| `/f manage`| Open the managment form | ⏰ | >= 2 | [`/f ma`] |
| `/f leave`| Open the managment form | ❌ | <= 2 | [`/f le`] |
| `/f `| Open the managment form, Or create form if not have faction | ⏰ | <= 2 |
---
### Discord.JS
### Web Page
