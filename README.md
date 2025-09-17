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
- `source` – source name  
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
        "total": 10,
        "per_page": 200,
        "current_page": 1,
        "last_page": 1,
        "articles": [
            {
                "id": 1,
                "title": "Borderlands 4 review – the chaotic, colourful shooter has finally grown up a little",
                "description": "",
                "source": "The Guardian",
                "category": "Games",
                "author": "Unknown",
                "published_at": "2025-09-17 03:59pm"
            },
            {
                "id": 2,
                "title": "What is new in UK-US tech deal and what will it mean for the British economy?",
                "description": "",
                "source": "The Guardian",
                "category": "Technology",
                "author": "Unknown",
                "published_at": "2025-09-17 02:29pm"
            },
            {
                "id": 3,
                "title": "AI will make the rich unfathomably richer. Is this really what we want? | Dustin Guastella",
                "description": "",
                "source": "The Guardian",
                "category": "Opinion",
                "author": "Unknown",
                "published_at": "2025-09-16 12:00pm"
            },
            {
                "id": 4,
                "title": "From cherry juice to white noise: I tested the most-hyped sleep aids – here’s what worked (and what didn’t)",
                "description": "",
                "source": "The Guardian",
                "category": "The filter",
                "author": "Unknown",
                "published_at": "2025-09-17 02:00pm"
            },
            {
                "id": 5,
                "title": "‘I have to do it’: Why one of the world’s most brilliant AI scientists left the US for China",
                "description": "",
                "source": "The Guardian",
                "category": "News",
                "author": "Unknown",
                "published_at": "2025-09-16 04:00am"
            },
            {
                "id": 6,
                "title": "Borderlands 4 review – the chaotic, colourful shooter has finally grown up a little",
                "description": "",
                "source": "The Guardian",
                "category": "Games",
                "author": "Unknown",
                "published_at": "2025-09-17 03:59pm"
            },
            {
                "id": 7,
                "title": "What is new in UK-US tech deal and what will it mean for the British economy?",
                "description": "",
                "source": "The Guardian",
                "category": "Technology",
                "author": "Unknown",
                "published_at": "2025-09-17 02:29pm"
            },
            {
                "id": 8,
                "title": "AI will make the rich unfathomably richer. Is this really what we want? | Dustin Guastella",
                "description": "",
                "source": "The Guardian",
                "category": "Opinion",
                "author": "Unknown",
                "published_at": "2025-09-16 12:00pm"
            },
            {
                "id": 9,
                "title": "From cherry juice to white noise: I tested the most-hyped sleep aids – here’s what worked (and what didn’t)",
                "description": "",
                "source": "The Guardian",
                "category": "The filter",
                "author": "Unknown",
                "published_at": "2025-09-17 02:00pm"
            },
            {
                "id": 10,
                "title": "‘I have to do it’: Why one of the world’s most brilliant AI scientists left the US for China",
                "description": "",
                "source": "The Guardian",
                "category": "News",
                "author": "Unknown",
                "published_at": "2025-09-16 04:00am"
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
        "id": 1,
        "title": "Borderlands 4 review – the chaotic, colourful shooter has finally grown up a little",
        "description": "",
        "source": "The Guardian",
        "category": "Games",
        "author": "Unknown",
        "published_at": "2025-09-17 03:59pm"
    }
}
```
