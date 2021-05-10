
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
Ranks:
 - The permissions are the same if you click on the form (`/f form`)
 - Recrue
	 - Permissions: 
		 - /f chat
	 - ID: 0
 - Member
	 - Permissions: 
		 - /f home
		 - /f chat
	 - ID: 1
 - Officier
	 - Permissions: 
		 - /f home
		 - /f chat
		 - /f manage
		 - /f invite
	 - ID: 2
 - Leader
	 - Permissions: All permissions
	 - ID: 3
---
__Faction features__:
| Command or Feature| Description | Status  | Rank  |
|--|--|--|--|
| `/f claim`| Protect a zone for that other factions can't break or put a block in it | ✅ | >= 2 |
| `/f create`| Create a faction (Cost: 500$) | ✅ | < 0 |
| `/f disband`| Delete your faction and claims actives | ❌ | 3 |
| `/f invite`| Invite a player into your faction | ✅ | >= 2 |
| `/f manage`| Open the managment form | ❌ | >= 2 |

### Discord.JS
### Web Page
