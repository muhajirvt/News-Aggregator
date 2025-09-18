# News Aggregator API

A Laravel 10-based API that aggregates articles from multiple external news sources (NewsAPI, NYTimes, Guardian) and provides filtering capabilities.

---

## Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/muhajirvt/News-Aggregator.git
cd News-Aggregator
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment Variables

- Create a `.env` file and copy the contents from `.env.example`
- Add your API keys in the `.env` file:

```
NEWSAPI_KEY=
NYT_KEY=
GUARDIAN_KEY=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

### 6. Start the Server

```bash
php artisan serve
```

---

## API Endpoints

All endpoints are prefixed with `/api`.

---

### 1. `GET /api/articles`

Fetch a paginated list of articles.

#### Parameters

- `search` – keyword in title or content  
- `category` – category name  
- `source_id` – source id   
- `author` – author name  
- `date_from` – published start date (`Y-m-d H:i:s`)  
- `date_to` – published end date (`Y-m-d H:i:s`)  
- `page` – page number  
- `per_page` – articles per page  

#### Sample Response

```json
{
    "success": true,
    "data": {
        "total": 100,
        "per_page": 10,
        "current_page": 1,
        "last_page": 10,
        "articles": [
            {
                "id": 27,
                "title": "Microsoft open-sources its 6502 version of BASIC from 1976",
                "description": "After years of unofficial copies of Microsoft’s 6502 BASIC floating around on the internet, the software giant has released the code under an open-source license. 6502 BASIC was one of Microsoft’s first pieces of software, adapted in 1976 by Microsoft cofound…",
                "source": "News API",
                "category": "Unknown",
                "author": "Tom warren",
                "published_at": "2025-09-04 10:22am"
            },
            {
                "id": 28,
                "title": "Elon Musk’s New Software Company Is the Opposite of Microsoft",
                "description": "\"It’s a tongue-in-cheek name, but the project is very real!\" Musk says.",
                "source": "News API",
                "category": "Unknown",
                "author": "Matt novak",
                "published_at": "2025-08-22 04:16pm"
            },
            {
                "id": 29,
                "title": "Is AI the end of software engineering or the next step in its evolution?",
                "description": "The first time I used ChatGPT to code, back in early 2023, I was reminded of \"The Monkey's Paw,\" a classic horror story about an accursed talisman that grants wishes, but always by the most malevolent path - the desired outcome arrives after exacting a brutal…",
                "source": "News API",
                "category": "Unknown",
                "author": "Sheon han",
                "published_at": "2025-09-01 09:35am"
            },
            {
                "id": 30,
                "title": "Garmin’s Top Training Features, Explained",
                "description": "Garmin has some of the best proprietary fitness software around. Here’s how to interpret all that meticulously gathered data.",
                "source": "News API",
                "category": "Unknown",
                "author": "Brent rose",
                "published_at": "2025-09-10 12:30pm"
            },
            {
                "id": 31,
                "title": "Amazon’s next tablet might run Android",
                "description": "Amazon is preparing to launch a new tablet that could run on Android instead of its custom FireOS software, according to a report from Reuters. Multiple sources tell the outlet that Amazon plans to release the “higher-end” Android tablet as early as next year…",
                "source": "News API",
                "category": "Unknown",
                "author": "Emma roth",
                "published_at": "2025-08-20 03:07pm"
            },
            {
                "id": 32,
                "title": "Is the Flipper Zero the next big car theft gadget?",
                "description": "404 Media has a report out about an underground software market that enables the Flipper Zero to be used to unlock a wide variety of vehicles, including Ford, Audi, Volkswagen, Subaru, Hyundai, Kia, and several other models. The hack works by intercepting and…",
                "source": "News API",
                "category": "Unknown",
                "author": "Andrew j. hawkins",
                "published_at": "2025-08-21 03:17pm"
            },
            {
                "id": 33,
                "title": "New York City Is Stuck With a $45 Million EV Fleet That’s Glitchy as Hell",
                "description": "Problems included suddenly losing power, the inability to exit in an emergency, issues with the gauges and icons on the dashboard, software that did not meet safety requirements, brakes not working, and \"unintended\" vehicle movement.",
                "source": "News API",
                "category": "Unknown",
                "author": "Riley gutiérrez mcdermid",
                "published_at": "2025-08-19 04:50pm"
            },
            {
                "id": 34,
                "title": "Vimeo to be acquired by Bending Spoons for $1.38 billion",
                "description": "Vimeo is getting bought up by Bending Spoons, a European software company that has amassed a growing portfolio of businesses, including Evernote, WeTransfer, and Meetup. Bending Spoons will pay $1.38 billion to acquire the video hosting platform, and it expec…",
                "source": "News API",
                "category": "Unknown",
                "author": "Emma roth",
                "published_at": "2025-09-10 03:22pm"
            },
            {
                "id": 35,
                "title": "Grammarly can now fix your Spanish and French grammar",
                "description": "For 16 years, a team of linguists carefully crafted and honed the grammar editing software Grammarly to match natural English language patterns. Now, the company is getting a big assist from AI to expand similar offerings to five more languages: Spanish, Fren…",
                "source": "News API",
                "category": "Unknown",
                "author": "Elissa welle",
                "published_at": "2025-09-10 12:51pm"
            },
            {
                "id": 36,
                "title": "iPhone 17 event live blog: on the ground at Apple’s keynote",
                "description": "It's time for another \"awe dropping\" Apple event. The company is expected to announce the iPhone 17 today, alongside some new Apple Watches and perhaps the AirPods Pro 3. We got a glimpse of some software at WWDC 2025, but today is all about the new hardware,…",
                "source": "News API",
                "category": "Unknown",
                "author": "Victoria song, allison johnson, jacob kastrenakes",
                "published_at": "2025-09-09 04:27pm"
            }
        ]
    }
}
```

---

### 2. `GET /api/articles/{id}`

Fetch a specific article using an ID.

#### Sample Response

```json
{
    "success": true,
    "data": {
        "id": 50,
        "title": "AI Is a Threat to the Entry-Level Job Market, Stanford Study Shows",
        "description": "Substantial declines in employment have occurred for early-career workers in occupations most exposed to AI, such as software development and customer support.",
        "source": "News API",
        "category": "Unknown",
        "author": "Dashia milden",
        "published_at": "2025-08-28 09:20pm"
    }
}
```
