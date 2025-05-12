USE seo_analysis;

CREATE TABLE analysis_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    keyword_density TEXT,
    readability_score FLOAT,
    headlines TEXT,
    meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
